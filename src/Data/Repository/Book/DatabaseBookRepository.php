<?php

namespace BookStore\Data\Repository\Book;

use BookStore\Business\Model\Book\Book;
use BookStore\Business\Repository\BookRepositoryInterface;
use BookStore\Infrastructure\Database\DatabaseConnection;
use PDOException;

class DatabaseBookRepository implements BookRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getBooksByAuthorId(int $authorId): ?array
    {
        $conn = DatabaseConnection::getInstance()->getConnection();

        $checkAuthorQuery = "SELECT 1 FROM Authors WHERE id = :id LIMIT 1";
        try {
            $checkStmt = $conn->prepare($checkAuthorQuery);
            $checkStmt->execute(['id' => $authorId]);

            if ($checkStmt->fetchColumn() === false) {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Database error checking author existence: " . $e->getMessage());

            return null;
        }

        $query = "
            SELECT
                id, title, year, author_id
            FROM
                Books
            WHERE
                author_id = :author_id ORDER BY year DESC, title ASC";
        try {
            $statement = $conn->prepare($query);
            $statement->execute(['author_id' => $authorId]);
            $rows = $statement->fetchAll();

            $books = [];
            foreach ($rows as $row) {
                $books[] = new Book(
                    (int)$row['id'],
                    $row['title'],
                    (int)$row['year'],
                    (int)$row['author_id']
                );
            }

            return $books;

        } catch (PDOException $e) {
            error_log("Database error fetching books by author: " . $e->getMessage());

            return [];
        }
    }

    /**
     * @inheritDoc
     */
    public function findBookById(int $bookId): ?Book
    {
        $query = "SELECT id, title, year, author_id FROM Books WHERE id = :id";
        try {
            $statement = DatabaseConnection::getInstance()->getConnection()->prepare($query);
            $statement->execute(['id' => $bookId]);
            $row = $statement->fetch();

            return new Book(
                (int)$row['id'],
                $row['title'],
                (int)$row['year'],
                (int)$row['author_id']
            );
        } catch (PDOException $e) {
            error_log($e->getMessage());

            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function addBook(Book $book): ?Book
    {
        $query = "INSERT INTO Books (title, year, author_id) VALUES (:title, :year, :author_id)";
        try {
            $statement = DatabaseConnection::getInstance()->getConnection()->prepare($query);
            $success = $statement->execute([
                'title' => $book->getTitle(),
                'year' => $book->getYear(),
                'author_id' => $book->getAuthorId()
            ]);

            if ($success) {
                $newBookId = DatabaseConnection::getInstance()->getConnection()->lastInsertId();
                return $this->findBookById((int)$newBookId);
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());

            return null;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function updateBook(Book $book): ?Book
    {
        $existingBook = $this->findBookById($book->getId());
        if (!$existingBook) {
            return null;
        }

        $query = "UPDATE Books SET title = :title, year = :year WHERE id = :id";
        try {
            $statement = DatabaseConnection::getInstance()->getConnection()->prepare($query);
            $success = $statement->execute([
                'title' => $book->getTitle(),
                'year' => $book->getYear(),
                'id' => $book->getId()
            ]);

            if ($success) {
                return $this->findBookById($book->getId());
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());

            return null;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function deleteBook(int $bookId): bool
    {
        $query = "DELETE FROM Books WHERE id = :id";
        try {
            $statement = DatabaseConnection::getInstance()->getConnection()->prepare($query);
            $statement->execute(['id' => $bookId]);
            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());

            return false;
        }
    }
}
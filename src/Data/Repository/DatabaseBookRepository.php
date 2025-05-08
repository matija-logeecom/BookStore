<?php

namespace BookStore\Data\Repository;

use BookStore\Business\Repository\BookRepositoryInterface;
use BookStore\Infrastructure\Database\DatabaseConnection;
use PDO;
use PDOException;

class DatabaseBookRepository implements BookRepositoryInterface
{
    private PDO $connection;

    public function __construct(DatabaseConnection $database)
    {
        $this->connection = $database->getConnection();
    }

    /**
     * @inheritDoc
     */
    public function getBooksByAuthorId(int $authorId): array
    {
        $query = "SELECT id, title, publication_year AS year FROM Books WHERE author_id = :author_id ORDER BY publication_year DESC, title ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute(['author_id' => $authorId]);
        return $statement->fetchAll();
    }

    /**
     * @inheritDoc
     */
    public function findBookById(int $bookId): ?array
    {
        $query = "SELECT id, title, publication_year AS year, author_id FROM Books WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute(['id' => $bookId]);
        $book = $statement->fetch();
        return $book ?: null;
    }

    /**
     * @inheritDoc
     */
    public function addBook(string $title, int $year, int $authorId): ?array
    {
        $query = "INSERT INTO Books (title, publication_year, author_id) VALUES (:title, :year, :author_id)";
        try {
            $statement = $this->connection->prepare($query);
            $success = $statement->execute([
                'title' => $title,
                'year' => $year,
                'author_id' => $authorId
            ]);

            if ($success) {
                $newBookId = $this->connection->lastInsertId();
                return $this->findBookById((int)$newBookId);
            }
        } catch (PDOException $e) {
            return null;
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function updateBook(int $bookId, string $title, int $year): ?array
    {
        $existingBook = $this->findBookById($bookId);
        if (!$existingBook) {
            return null;
        }

        $query = "UPDATE Books SET title = :title, publication_year = :year WHERE id = :id";
        try {
            $statement = $this->connection->prepare($query);
            $success = $statement->execute([
                'title' => $title,
                'year' => $year,
                'id' => $bookId
            ]);

            if ($success) {
                return $this->findBookById($bookId);
            }
        } catch (PDOException $e) {
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
            $statement = $this->connection->prepare($query);
            $statement->execute(['id' => $bookId]);
            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}
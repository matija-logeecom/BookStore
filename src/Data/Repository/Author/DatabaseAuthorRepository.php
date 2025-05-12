<?php

namespace BookStore\Data\Repository\Author;

use BookStore\Business\Model\Author\Author;
use BookStore\Business\Repository\AuthorRepositoryInterface;
use BookStore\Infrastructure\Database\DatabaseConnection;
use PDOException;

/*
 * Class containing logic for working with Author database
 */

class DatabaseAuthorRepository implements AuthorRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        $query = "
            SELECT 
                Authors.id, 
                Authors.name, 
                COUNT(Books.id) as bookCount
            FROM 
                Authors
            LEFT JOIN 
                Books ON Authors.id = Books.author_id
            GROUP BY
                Authors.id, Authors.name
            ORDER BY
                Authors.name ASC";

        $statement = DatabaseConnection::getInstance()->getConnection()->query($query);
        $rows = $statement->fetchAll();

        $authors = [];
        foreach ($rows as $row) {
            $authors[] = new Author((int)$row['id'], (string)$row['name'], (int)$row['bookCount']);
        }

        return $authors;
    }

    /**
     * @inheritDoc
     */
    public function addAuthor(Author $author): bool
    {
        $query = "INSERT INTO Authors (name) VALUES (:name)";
        try {
            $statement = DatabaseConnection::getInstance()->getConnection()->prepare($query);
            return $statement->execute(['name' => $author->getName()]);
        } catch (PDOException $e) {
            error_log($e->getMessage());

            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function editAuthor(Author $author): bool
    {
        $query = "UPDATE Authors SET name = :name WHERE id = :id";
        try {
            $statement = DatabaseConnection::getInstance()->getConnection()->prepare($query);
            return $statement->execute(['name' => $author->getName(), 'id' => $author->getId()]);
        } catch (PDOException $e) {
            error_log($e->getMessage());

            return false;
        }

    }

    /**
     * @inheritDoc
     */
    public function deleteAuthor(int $authorId): bool
    {
        $query = "DELETE FROM Authors WHERE id = :id";
        try {
            $statement = DatabaseConnection::getInstance()->getConnection()->prepare($query);
            return $statement->execute(['id' => $authorId]);
        } catch (PDOException $e) {
            error_log($e->getMessage());

            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function getAuthorById(int $authorId): ?Author
    {
        $query = "SELECT id, name FROM Authors WHERE id = :id LIMIT 1";
        try {
            $statement = DatabaseConnection::getInstance()->getConnection()->prepare($query);
            $statement->execute(['id' => $authorId]);
            $row = $statement->fetch();

            if (!$row) {
                return null;
            }

            return new Author((int)$row['id'], $row['name']);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }

    }
}

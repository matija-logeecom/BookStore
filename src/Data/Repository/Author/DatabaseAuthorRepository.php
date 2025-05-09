<?php

namespace BookStore\Data\Repository\Author;

use BookStore\Business\Model\Author\Author;
use BookStore\Business\Repository\AuthorRepositoryInterface;
use BookStore\Infrastructure\Database\DatabaseConnection;

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
    public function addAuthor(Author $author): void
    {
        $query = "INSERT INTO Authors (name) VALUES (:name)";
        $statement = DatabaseConnection::getInstance()->getConnection()->prepare($query);
        $statement->execute(['name' => $author->getName()]);
    }

    /**
     * @inheritDoc
     */
    public function editAuthor(Author $author): void
    {
        $query = "UPDATE Authors SET name = :name WHERE id = :id";
        $statement = DatabaseConnection::getInstance()->getConnection()->prepare($query);
        $statement->execute(['name' => $author->getName(), 'id' => $author->getId()]);
    }

    /**
     * @inheritDoc
     */
    public function deleteAuthor(int $authorId): void
    {
        $query = "DELETE FROM Authors WHERE id = :id";
        $statement = DatabaseConnection::getInstance()->getConnection()->prepare($query);
        $statement->execute(['id' => $authorId]);
    }

    /**
     * @inheritDoc
     */
    public function getAuthorById(int $authorId): ?Author
    {
        $query = "SELECT id, name FROM Authors WHERE id = :id LIMIT 1";
        $statement = DatabaseConnection::getInstance()->getConnection()->prepare($query);
        $statement->execute(['id' => $authorId]);
        $row = $statement->fetch();

        return new Author((int)$row['id'], $row['name']);
    }
}
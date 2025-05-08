<?php

namespace BookStore\Data\Repository;

use BookStore\Business\Repository\AuthorRepositoryInterface;
use BookStore\Infrastructure\Database\DatabaseConnection;
use PDO;

class DatabaseAuthorRepository implements AuthorRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
       $this->connection = $connection;
    }

    /**
     * Retrieves a list of all authors in current session
     *
     * @return array
     */
    public function getAll(): array
    {
        $query = "SELECT * FROM Authors";
        $statement = DatabaseConnection::getInstance()->getConnection()->query($query);
        return $statement->fetchAll();
    }

    /**
     * Adds an author with provided name to current session
     *
     * @param $fullName
     *
     * @return void
     */
    public function addAuthor($fullName): void
    {
        $query = "INSERT INTO Authors (name) VALUES (:name)";
        $statement = $this->connection->prepare($query);
        $statement->execute(['name' => $fullName]);
    }

    /**
     * Changes name of author with provided id to provided name
     *
     * @param $authorId
     * @param $fullName
     *
     * @return void
     */
    public function editAuthor($authorId, $fullName): void
    {
        $query = "UPDATE Authors SET name = :name WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute(['name' => $fullName, 'id' => $authorId]);
    }

    /**
     * Deletes an author with provided id from current session
     *
     * @param int $authorId
     *
     * @return void
     */
    public function deleteAuthor(int $authorId): void
    {
        $query = "DELETE FROM Authors WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute(['id' => $authorId]);
    }

    /**
     * Retrieves an author with provided id from current session
     *
     * @param int $authorId
     *
     * @return array|null
     */
    public function getAuthorById(int $authorId): ?array
    {
        $authors = $this->getAll();

        $result = current(array_filter($authors, fn($a) => $a['id'] === $authorId));
        return $result === false ? null : $result;
    }
}
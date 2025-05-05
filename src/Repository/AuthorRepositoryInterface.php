<?php

namespace BookStore\Repository;

/**
 * Defines the contract for managing author data
 * Implementations can store data in sessions or MySQL database
 */
interface AuthorRepositoryInterface
{
    /**
     * Retrieves a list of all authors
     *
     * @return array
     */
    public function getAll(): array;

    /**
     * Adds a new author with the provided name
     *
     * @param string $fullName
     *
     * @return void
     */
    public function addAuthor(string $fullName): void;

    /**
     * Changes the name of the author with the provided ID
     *
     * @param int $authorId
     * @param string $fullName
     *
     * @return void
     */
    public function editAuthor(int $authorId, string $fullName): void;

    /**
     * Deletes author with the provided ID
     *
     * @param int $authorId
     *
     * @return void
     */
    public function deleteAuthor(int $authorId): void;

    /**
     * Retrieves an author with provided ID
     *
     * @param int $authorId
     *
     * @return array|null
     */
    public function getAuthorById(int $authorId): ?array;
}
<?php

namespace BookStore\Business\Repository;

use BookStore\Business\Model\Author;

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
     * @param Author $author
     *
     * @return void
     */
    public function addAuthor(Author $author): void;

    /**
     * Changes the name of the author with the provided ID
     *
     * @param Author $author
     *
     * @return void
     */
    public function editAuthor(Author $author): void;

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
     * @return Author|null
     */
    public function getAuthorById(int $authorId): ?Author;
}
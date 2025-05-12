<?php

namespace BookStore\Business\Repository;

use BookStore\Business\Model\Author\Author;

/**
 * Defines the contract for managing author data
 * Implementations can store data in sessions or MySQL database
 */
interface AuthorRepositoryInterface
{
    /**
     * Retrieves a list of all authors
     *
     * @return Author[]
     */
    public function getAll(): array;

    /**
     * Adds a new author with the provided name
     *
     * @param Author $author
     *
     * @return bool
     */
    public function addAuthor(Author $author): bool;

    /**
     * Changes the name of the author with the provided ID
     *
     * @param Author $author
     *
     * @return bool
     */
    public function editAuthor(Author $author): bool;

    /**
     * Deletes author with the provided ID
     *
     * @param int $authorId
     *
     * @return bool
     */
    public function deleteAuthor(int $authorId): bool;

    /**
     * Retrieves an author with provided ID
     *
     * @param int $authorId
     *
     * @return Author|null
     */
    public function getAuthorById(int $authorId): ?Author;
}

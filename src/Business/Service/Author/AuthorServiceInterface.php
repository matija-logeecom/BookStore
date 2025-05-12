<?php

namespace BookStore\Business\Service\Author;

use BookStore\Business\Model\Author\Author;

interface AuthorServiceInterface
{
    /**
     * Retrieves a list of all authors in current session
     *
     * @return Author[]
     */
    public function getAuthorList(): array;

    /**
     * Adds an author with provided name to current session
     *
     * @param Author $author
     * @param array $errors
     *
     * @return bool
     */
    public function addAuthor(Author $author, array &$errors): bool;

    /**
     * Changes name of author with provided id to provided name
     *
     * @param Author $author
     * @param array $errors
     *
     * @return bool
     */
    public function editAuthor(Author $author, array &$errors): bool;

    /**
     * Deletes an author with provided id from current session
     *
     * @param int $authorId
     *
     * @return bool
     */
    public function deleteAuthor(int $authorId): bool;

    /**
     * Returns an author with provided id
     *
     * @param int $id
     *
     * @return Author|null
     */
    public function getAuthorById(int $id): ?Author;
}

<?php

namespace BookStore\Business\Service;

use BookStore\Business\Model\Author;

interface AuthorServiceInterface
{
    /**
     * Retrieves a list of all authors in current session
     *
     * @return array
     */
    public function getAuthorList(): array;

    /**
     * Adds an author with provided name to current session
     *
     * @param Author $author
     * @param array $errors
     *
     * @return void
     */
    public function addAuthor(Author $author, array &$errors): void;

    /**
     * Changes name of author with provided id to provided name
     *
     * @param Author $author
     * @param $errors
     *
     * @return void
     */
    public function editAuthor(Author $author, &$errors): void;

    /**
     * Deletes an author with provided id from current session
     *
     * @param int $authorId
     *
     * @return void
     */
    public function deleteAuthor(int $authorId): void;

    /**
     * Returns an author with provided id
     *
     * @param int $id
     *
     * @return array|null
     */
    public function getAuthorById(int $id): ?Author;
}
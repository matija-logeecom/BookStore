<?php

namespace BookStore\Service;

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
     * @param $firstName
     * @param $lastName
     * @param array $errors
     *
     * @return void
     */
    public function addAuthor($firstName, $lastName, array &$errors): void;

    /**
     * Changes name of author with provided id to provided name
     *
     * @param $authorId
     * @param $firstName
     * @param $lastName
     * @param $errors
     *
     * @return void
     */
    public function editAuthor($authorId, $firstName, $lastName, &$errors): void;

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
     * @param $id
     *
     * @return array|null
     */
    public function getAuthorById($id): ?array;
}
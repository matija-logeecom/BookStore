<?php

namespace BookStore\Service;

use BookStore\Repository\BookRepositoryInterface;

interface BookServiceInterface
{
    /**
     * Retrieves a list of books for a given author.
     *
     * @param int $authorId
     *
     * @return array
     */
    public function getBooksByAuthor(int $authorId): array;
    /**
     * Retrieves a single book by its ID.
     *
     * @param int $bookId
     *
     * @return array|null
     */
    public function getBookById(int $bookId): ?array;

    /**
     * Adds a new book after validation.
     *
     * @param string $title
     * @param string $yearInput The year as a string from user input.
     * @param int $authorId
     * @param array &$errors Passed by reference to populate with validation errors.
     *
     * @return array|null The new book data if successful, null otherwise.
     */
    public function addBook(string $title, string $yearInput, int $authorId, array &$errors): ?array;

    /**
     * Edits an existing book after validation.
     *
     * @param int $bookId
     * @param string $title
     * @param string $yearInput The year as a string from user input.
     * @param array &$errors Passed by reference to populate with validation errors.
     *
     * @return array|null The updated book data if successful, null otherwise.
     */
    public function editBook(int $bookId, string $title, string $yearInput, array &$errors): ?array;

    /**
     * Deletes a book.
     *
     * @param int $bookId
     *
     * @return bool True on success, false otherwise.
     */
    public function deleteBook(int $bookId): bool;
}
<?php

namespace BookStore\Repository;

/**
 * Defines the contract for managing book data.
 */
interface BookRepositoryInterface
{
    /**
     * Retrieves all books for a specific author.
     *
     * @param int $authorId The ID of the author.
     *
     * @return array A list of books, each as an associative array.
     */
    public function getBooksByAuthorId(int $authorId): array;

    /**
     * Finds a single book by its ID.
     *
     * @param int $bookId The ID of the book.
     *
     * @return array|null The book data as an associative array, or null if not found.
     */
    public function findBookById(int $bookId): ?array;

    /**
     * Adds a new book.
     *
     * @param string $title The title of the book.
     * @param int $year The publication year of the book.
     * @param int $authorId The ID of the author of the book.
     *
     * @return array|null The newly created book data as an associative array (including its new ID), or null on failure.
     */
    public function addBook(string $title, int $year, int $authorId): ?array;

    /**
     * Updates an existing book.
     *
     * @param int $bookId The ID of the book to update.
     * @param string $title The new title of the book.
     * @param int $year The new publication year of the book.
     *
     * @return array|null The updated book data as an associative array, or null on failure or if not found.
     */
    public function updateBook(int $bookId, string $title, int $year): ?array;

    /**
     * Deletes a book.
     *
     * @param int $bookId The ID of the book to delete.
     *
     * @return bool True on success, false on failure or if not found.
     */
    public function deleteBook(int $bookId): bool;
}
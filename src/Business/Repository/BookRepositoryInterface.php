<?php

namespace BookStore\Business\Repository;

use BookStore\Business\Model\Book\Book;

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
     * @return Book[]|null A list of books, each as an associative array.
     */
    public function getBooksByAuthorId(int $authorId): ?array;

    /**
     * Finds a single book by its ID.
     *
     * @param int $bookId The ID of the book.
     *
     * @return Book|null The book data as an associative array, or null if not found.
     */
    public function findBookById(int $bookId): ?Book;

    /**
     * Adds a new book.
     *
     * @param Book $book
     *
     * @return Book|null The newly created book data as an associative array (including its new ID), or null on failure.
     */
    public function addBook(Book $book): ?Book;

    /**
     * Updates an existing book.
     *
     * @param Book $book
     *
     * @return Book|null The updated book data as an associative array, or null on failure or if not found.
     */
    public function updateBook(Book $book): ?Book;

    /**
     * Deletes a book.
     *
     * @param int $bookId The ID of the book to delete.
     *
     * @return bool True on success, false on failure or if not found.
     */
    public function deleteBook(int $bookId): bool;
}

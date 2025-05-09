<?php

namespace BookStore\Business\Service;

use BookStore\Business\Model\Author;
use BookStore\Business\Model\Book;

interface BookServiceInterface
{
    /**
     * Retrieves a list of books for a given author.
     *
     * @param Author $author
     *
     * @return Book[]
     */
    public function getBooksByAuthor(Author $author): array;

    /**
     * Retrieves a single book by its ID.
     *
     * @param int $bookId
     *
     * @return Book|null
     */
    public function getBookById(int $bookId): ?Book;

    /**
     * Adds a new book after validation.
     *
     * @param Book $book
     * @param array $errors Passed by reference to populate with validation errors.
     *
     * @return Book|null The new book data if successful, null otherwise.
     */
    public function addBook(Book $book, array &$errors): ?Book;

    /**
     * Edits an existing book after validation.
     *
     * @param Book $book
     * @param array $errors Passed by reference to populate with validation errors.
     *
     * @return Book|null The updated book data if successful, null otherwise.
     */
    public function editBook(Book $book, array &$errors): ?Book;

    /**
     * Deletes a book.
     *
     * @param int $bookId
     *
     * @return bool True on success, false otherwise.
     */
    public function deleteBook(int $bookId): bool;
}
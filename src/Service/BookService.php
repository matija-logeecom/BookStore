<?php

namespace BookStore\Service;

use BookStore\Repository\BookRepositoryInterface;

class BookService
{
    private BookRepositoryInterface $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Retrieves a list of books for a given author.
     *
     * @param int $authorId
     *
     * @return array
     */
    public function getBooksByAuthor(int $authorId): array
    {
        return $this->bookRepository->getBooksByAuthorId($authorId);
    }

    /**
     * Retrieves a single book by its ID.
     *
     * @param int $bookId
     *
     * @return array|null
     */
    public function getBookById(int $bookId): ?array
    {
        return $this->bookRepository->findBookById($bookId);
    }

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
    public function addBook(string $title, string $yearInput, int $authorId, array &$errors): ?array
    {
        if (!$this->validateBook($title, $yearInput, $authorId, $errors)) {
            return null;
        }

        $year = (int)$yearInput;
        return $this->bookRepository->addBook(trim($title), $year, $authorId);
    }

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
    public function editBook(int $bookId, string $title, string $yearInput, array &$errors): ?array
    {
        if (!$this->validateBook($title, $yearInput, null, $errors)) {
            return null;
        }

        $year = (int)$yearInput;
        return $this->bookRepository->updateBook($bookId, trim($title), $year);
    }

    /**
     * Deletes a book.
     *
     * @param int $bookId
     *
     * @return bool True on success, false otherwise.
     */
    public function deleteBook(int $bookId): bool
    {
        return $this->bookRepository->deleteBook($bookId);
    }

    /**
     * Validates book data.
     *
     * @param string $title
     * @param string $yearInput
     * @param int|null $authorId For new books; null if not validating author_id (e.g., during edit).
     * @param array &$errors
     *
     * @return bool True if valid, false otherwise.
     */
    private function validateBook(string $title, string $yearInput, ?int $authorId, array &$errors): bool
    {
        $title = trim($title);
        if (empty($title)) {
            $errors['title'] = "Title is required.";
        } elseif (strlen($title) > 100) {
            $errors['title'] = "Title must be ≤ 255 characters.";
        }

        $yearInput = trim($yearInput);
        if (empty($yearInput)) {
            $errors['year'] = "Year is required.";
        } elseif (!is_numeric($yearInput)) {
            $errors['year'] = "Year must be a number.";
        } else {
            $year = (int)$yearInput;
            if ($year < -5000 || $year > 999999 || $year === 0) {
                $errors['year'] = "Please enter a valid publication year.";
            }
        }

        if ($authorId !== null) {
            if (empty($authorId)) {
                $errors['author_id'] = "Author ID is required.";
            } elseif (!is_numeric($authorId) || $authorId <= 0) {
                $errors['author_id'] = "Invalid Author ID.";
            }
        }

        return empty($errors);
    }
}
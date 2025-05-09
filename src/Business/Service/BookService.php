<?php

namespace BookStore\Business\Service;

use BookStore\Business\Model\Author;
use BookStore\Business\Repository\BookRepositoryInterface;
use BookStore\Business\Model\Book;

class BookService implements BookServiceInterface
{
    private BookRepositoryInterface $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @inheritDoc
     */
    public function getBooksByAuthor(Author $author): array
    {
        return $this->bookRepository->getBooksByAuthorId($author->getId());
    }

    /**
     * @inheritDoc
     */
    public function getBookById(int $bookId): ?Book
    {
        return $this->bookRepository->findBookById($bookId);
    }

    /**
     * @inheritDoc
     */
    public function addBook(Book $book, array &$errors): ?Book
    {
        if (!$this->validateBook($book, $errors)) {
            return null;
        }

        return $this->bookRepository->addBook($book);
    }

    /**
     * @inheritDoc
     */
    public function editBook(Book $book, array &$errors): ?Book
    {
        if (!$this->validateBook($book, $errors)) {
            return null;
        }

        return $this->bookRepository->updateBook($book);
    }

    /**
     * @inheritDoc
     */
    public function deleteBook(int $bookId): bool
    {
        return $this->bookRepository->deleteBook($bookId);
    }

    /**
     * @param Book $book
     * @param array &$errors
     *
     * @return bool True if valid, false otherwise.
     */
    private function validateBook(Book $book, array &$errors): bool
    {
        $title = trim($book->getTitle());
        $title = trim($title);
        if (empty($title)) {
            $errors['title'] = "Title is required.";
        } elseif (strlen($title) > 100) {
            $errors['title'] = "Title must be ≤ 255 characters.";
        }

        $yearInput = trim($book->getYear());
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

        $authorId = $book->getAuthorId();
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
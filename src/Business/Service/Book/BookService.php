<?php

namespace BookStore\Business\Service\Book;

use BookStore\Business\Model\Author\Author;
use BookStore\Business\Model\Book\Book;
use BookStore\Business\Repository\BookRepositoryInterface;

/*
 * Class for handling service logic for books
 */

class BookService implements BookServiceInterface
{
    private BookRepositoryInterface $bookRepository;

    /**
     * Constructs Book Service instance
     *
     * @param BookRepositoryInterface $bookRepository
     */
    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @inheritDoc
     */
    public function getBooksByAuthor(Author $author): ?array
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
        if (!$this->isValidBook($book, $errors)) {
            return null;
        }

        return $this->bookRepository->addBook($book);
    }

    /**
     * @inheritDoc
     */
    public function editBook(Book $book, array &$errors): ?Book
    {
        if (!$this->isValidBook($book, $errors)) {
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
     * Checks if the book parameters are valid
     *
     * @param Book $book
     * @param array &$errors
     *
     * @return bool
     */
    private function isValidBook(Book $book, array &$errors): bool
    {
        $title = trim($book->getTitle());
        $title = trim($title);
        if (empty($title)) {
            $errors['title'] = "Title is required.";
        }
        if (strlen($title) > 100) {
            $errors['title'] = "Title must be ≤ 255 characters.";
        }

        $yearInput = trim($book->getYear());
        if (empty($yearInput)) {
            $errors['year'] = "Year is required.";
        }
        if (!is_numeric($yearInput)) {
            $errors['year'] = "Year must be a number.";
        } else {
            if ((int)$yearInput < -5000 || (int)$yearInput > 999999 || (int)$yearInput === 0) {
                $errors['year'] = "Please enter a valid publication year.";
            }
        }

        $authorId = $book->getAuthorId();
        if (empty($authorId)) {
            $errors['author_id'] = "Author ID is required.";
        }
        if (!is_numeric($authorId) || $authorId <= 0) {
            $errors['author_id'] = "Invalid Author ID.";
        }

        return empty($errors);
    }
}

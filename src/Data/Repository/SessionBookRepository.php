<?php

namespace BookStore\Data\Repository;

use BookStore\Business\Repository\BookRepositoryInterface;

class SessionBookRepository implements BookRepositoryInterface
{
    public function __construct()
    {
        if (!isset($_SESSION['books'])) {
            $_SESSION['books'] = [];
        }
        if (!isset($_SESSION['next_book_id'])) {
            $_SESSION['next_book_id'] = 1;
        }
    }

    /**
     * @inheritDoc
     */
    public function getBooksByAuthorId(int $authorId): array
    {
        $authorBooks = [];
        foreach ($_SESSION['books'] as $book) {
            if ($book['author_id'] === $authorId) {
                $authorBooks[] = $book;
            }
        }
        usort($authorBooks, function ($a, $b) {
            if ($a['year'] == $b['year']) {
                return strcmp($a['title'], $b['title']);
            }
            return $b['year'] <=> $a['year'];
        });
        return $authorBooks;
    }

    /**
     * @inheritDoc
     */
    public function findBookById(int $bookId): ?array
    {
        foreach ($_SESSION['books'] as $book) {
            if ($book['id'] === $bookId) {
                return $book;
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function addBook(string $title, int $year, int $authorId): ?array
    {
        $newBookId = $_SESSION['next_book_id']++;
        $newBook = [
            'id' => $newBookId,
            'title' => $title,
            'year' => $year,
            'author_id' => $authorId
        ];
        $_SESSION['books'][] = $newBook;
        return $newBook;
    }

    /**
     * @inheritDoc
     */
    public function updateBook(int $bookId, string $title, int $year): ?array
    {
        foreach ($_SESSION['books'] as $key => $book) {
            if ($book['id'] === $bookId) {
                $_SESSION['books'][$key]['title'] = $title;
                $_SESSION['books'][$key]['year'] = $year;
                // Note: author_id is not updated by this method as per current interface
                return $_SESSION['books'][$key];
            }
        }
        return null; // Book not found
    }

    /**
     * @inheritDoc
     */
    public function deleteBook(int $bookId): bool
    {
        foreach ($_SESSION['books'] as $key => $book) {
            if ($book['id'] === $bookId) {
                unset($_SESSION['books'][$key]);
                return true;
            }
        }
        return false;
    }
}
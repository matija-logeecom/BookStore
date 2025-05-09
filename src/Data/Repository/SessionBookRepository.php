<?php

namespace BookStore\Data\Repository;

use BookStore\Business\Model\Book;
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

        $bookModels = [];
        foreach($authorBooks as $book) {
            $bookModels[] = new Book($book['id'], $book['title'], $book['year'], $book['author_id']);
        }

        return $bookModels;
    }

    /**
     * @inheritDoc
     */
    public function findBookById(int $bookId): ?Book
    {
        foreach ($_SESSION['books'] as $book) {
            if ($book['id'] === $bookId) {
                return new Book($book['id'], $book['title'], $book['year'], $book['author_id']);
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function addBook(Book $book): ?Book
    {
        $newBookId = $_SESSION['next_book_id']++;
        $newBook = [
            'id' => $newBookId,
            'title' => $book->getTitle(),
            'year' => $book->getYear(),
            'author_id' => $book->getAuthorId(),
        ];
        $_SESSION['books'][] = $newBook;

        return new Book($newBookId, $book->getTitle(), $book->getYear(), $book->getAuthorId());
    }

    /**
     * @inheritDoc
     */
    public function updateBook(Book $book): ?Book
    {
        foreach ($_SESSION['books'] as $key => $value) {
            if ($value['id'] === $book->getId()) {
                $_SESSION['books'][$key]['title'] = $book->getTitle();
                $_SESSION['books'][$key]['year'] = $book->getYear();

                return new Book($book->getId(), $book->getTitle(), $book->getYear(), $book->getAuthorId());
            }
        }
        return null;
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
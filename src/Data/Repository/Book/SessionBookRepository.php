<?php

namespace BookStore\Data\Repository\Book;

use BookStore\Business\Model\Book\Book;
use BookStore\Business\Repository\BookRepositoryInterface;
use BookStore\Infrastructure\Session\SessionManager;

class SessionBookRepository implements BookRepositoryInterface
{
    public function __construct()
    {
        if (!SessionManager::getInstance()->has('books')) {
            SessionManager::getInstance()->set('books', []);
        }

        if (!SessionManager::getInstance()->has('next_book_id')) {
            SessionManager::getInstance()->set('next_book_id', 1);
        }
    }

    /**
     * @inheritDoc
     */
    public function getBooksByAuthorId(int $authorId): array
    {
        $authorBooks = [];
        foreach (SessionManager::getInstance()->get('books') as $book) {
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
        foreach ($authorBooks as $book) {
            $bookModels[] = new Book($book['id'], $book['title'], $book['year'], $book['author_id']);
        }

        return $bookModels;
    }

    /**
     * @inheritDoc
     */
    public function findBookById(int $bookId): ?Book
    {
        foreach (SessionManager::getInstance()->get('books') as $book) {
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
        $books = SessionManager::getInstance()->get('books');
        $newBookId = SessionManager::getInstance()->get('next_book_id');
        $newBook = [
            'id' => $newBookId,
            'title' => $book->getTitle(),
            'year' => $book->getYear(),
            'author_id' => $book->getAuthorId(),
        ];

        $books[] = $newBook;
        SessionManager::getInstance()->set('next_book_id', $newBookId + 1);
        SessionManager::getInstance()->set('books', $books);

        return new Book($newBookId, $book->getTitle(), $book->getYear(), $book->getAuthorId());
    }

    /**
     * @inheritDoc
     */
    public function updateBook(Book $book): ?Book
    {
        $books = SessionManager::getInstance()->get('books');
        foreach ($books as $oldBook) {
            if ($oldBook['id'] === $book->getId()) {
                $editedBook = [
                    'id' => $book->getId(),
                    'title' => $book->getTitle(),
                    'year' => $book->getYear(),
                    'author_id' => $book->getAuthorId()
                ];
                $filteredBooks = array_filter($books, fn($b) => $b['id'] !== $book->getId());
                $filteredBooks[] = $editedBook;
                SessionManager::getInstance()->set('books', $filteredBooks);

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
        $books = SessionManager::getInstance()->get('books');
        $filteredBooks = array_filter($books, fn($book) => $book['id'] !== $bookId);
        SessionManager::getInstance()->set('books', $filteredBooks);

        return count($books) !== count($filteredBooks);
    }
}
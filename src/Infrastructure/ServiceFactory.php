<?php

namespace BookStore\Infrastructure;

use BookStore\Controller\AuthorController;
use BookStore\Service\AuthorService;
use BookStore\Repository\AuthorRepositoryInterface;
use BookStore\Repository\DatabaseAuthorRepository;

use BookStore\Controller\BookController;
use BookStore\Service\BookService;
use BookStore\Repository\BookRepositoryInterface;
use BookStore\Repository\SessionBookRepository;

use BookStore\Database\DatabaseConnection;

class ServiceFactory
{
    /**
     * Create an DatabaseAuthorRepository instance
     *
     * @return AuthorRepositoryInterface
     */
    public function createAuthorRepository(): AuthorRepositoryInterface
    {
        $database = DatabaseConnection::getInstance();

        return new DatabaseAuthorRepository($database);
    }

    /**
     * Create an AuthorService instance
     *
     * @param AuthorRepositoryInterface $authorRepository
     *
     * @return AuthorService
     */
    public function createAuthorService(AuthorRepositoryInterface $authorRepository, BookRepositoryInterface $bookRepository): AuthorService
    {
        return new AuthorService($authorRepository, $bookRepository);
    }

    /**
     * Create an AuthorController instance
     *
     * @param AuthorService $authorService
     *
     * @return AuthorController
     */
    public function createAuthorController(AuthorService $authorService): AuthorController
    {
        return new AuthorController($authorService);
    }

    /**
     * Create a BookRepositoryInterface instance.
     *
     * @return BookRepositoryInterface
     */
    public function createBookRepository(): BookRepositoryInterface
    {
        return new SessionBookRepository();
    }

    /**
     * Create a BookService instance.
     *
     * @param BookRepositoryInterface $bookRepository
     *
     * @return BookService
     */
    public function createBookService(BookRepositoryInterface $bookRepository): BookService
    {
        return new BookService($bookRepository);
    }

    /**
     * Create a BookController instance.
     *
     * @param BookService $bookService
     *
     * @return BookController
     */
    public function createBookController(BookService $bookService): BookController
    {
        return new BookController($bookService);
    }
}
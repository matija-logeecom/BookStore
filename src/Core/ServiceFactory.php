<?php

namespace BookStore\Core;

use BookStore\Controller\AuthorController;
use BookStore\Service\AuthorService;
use BookStore\Repository\AuthorRepository;
use BookStore\Database\DatabaseConnection;

class ServiceFactory
{
    /**
     * Create an AuthorRepository instance
     *
     * @return AuthorRepository
     */
    public function createAuthorRepository(): AuthorRepository
    {
        $database = DatabaseConnection::getInstance();
        return new AuthorRepository($database);
    }

    /**
     * Create an AuthorService instance
     *
     * @param AuthorRepository $authorRepository
     * @return AuthorService
     */
    public function createAuthorService(AuthorRepository $authorRepository): AuthorService
    {
        return new AuthorService($authorRepository);
    }

    /**
     * Create an AuthorController instance
     *
     * @param AuthorService $authorService
     * @return AuthorController
     */
    public function createAuthorController(AuthorService $authorService): AuthorController
    {
        return new AuthorController($authorService);
    }
}
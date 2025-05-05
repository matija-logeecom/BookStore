<?php

namespace BookStore\Infrastructure;

use BookStore\Controller\AuthorController;
use BookStore\Service\AuthorService;
use BookStore\Repository\AuthorRepositoryInterface;
use BookStore\Repository\DatabaseAuthorRepository;
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
    public function createAuthorService(AuthorRepositoryInterface $authorRepository): AuthorService
    {
        return new AuthorService($authorRepository);
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
}
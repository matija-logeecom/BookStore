<?php

namespace BookStore\Core;

use BookStore\Controller\AuthorController;
use BookStore\Service\AuthorService;
use BookStore\Repository\AuthorRepository;

class ServiceFactory
{
    /**
     * Create an AuthorRepository instance
     *
     * @return AuthorRepository
     */
    public function createAuthorRepository(): AuthorRepository
    {
        return new AuthorRepository();
    }

    /**
     * Create an AuthorService instance
     *
     * @return AuthorService
     */
    public function createAuthorService(): AuthorService
    {
        $authorRepository = self::createAuthorRepository();
        return new AuthorService($authorRepository);
    }

    /**
     * Create an AuthorController instance
     *
     * @return AuthorController
     */
    public function createAuthorController(): AuthorController
    {
        $authorService = self::createAuthorService();
        return new AuthorController($authorService);
    }
}
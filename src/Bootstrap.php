<?php

namespace BookStore;

use BookStore\Business\Repository\AuthorRepositoryInterface;
use BookStore\Business\Repository\BookRepositoryInterface;
use BookStore\Business\Service\Author\AuthorService;
use BookStore\Business\Service\Author\AuthorServiceInterface;
use BookStore\Business\Service\Book\BookService;
use BookStore\Business\Service\Book\BookServiceInterface;
use BookStore\Data\Repository\Author\DatabaseAuthorRepository;
use BookStore\Data\Repository\Book\DatabaseBookRepository;
use BookStore\Infrastructure\DI\ServiceRegistry;
use BookStore\Presentation\Controller\AuthorController;
use BookStore\Presentation\Controller\BookController;
use Exception;

class Bootstrap
{
    /**
     * @return void
     *
     * @throws Exception
     */
    public static function init(): void
    {
        self::registerRepositories();
        self::registerServices();
        self::registerControllers();
    }

    /**
     * Creates and sets repositories
     *
     * @return void
     */
    private static function registerRepositories(): void
    {
        ServiceRegistry::set(BookRepositoryInterface::class,
            new DatabaseBookRepository()
        );

        ServiceRegistry::set(AuthorRepositoryInterface::class,
            new DatabaseAuthorRepository()
        );
    }

    /**
     * Creates and sets services
     *
     * @throws Exception
     */
    private static function registerServices(): void
    {
        ServiceRegistry::set(
            BookServiceInterface::class,
            new BookService(ServiceRegistry::get(BookRepositoryInterface::class)
            )
        );

        ServiceRegistry::set(AuthorServiceInterface::class,
            new AuthorService(
                ServiceRegistry::get(AuthorRepositoryInterface::class),
                ServiceRegistry::get(BookRepositoryInterface::class)
            )
        );
    }

    /**
     * Creates and sets controllers
     *
     * @throws Exception
     */
    private static function registerControllers(): void
    {
        ServiceRegistry::set(BookController::class,
            new BookController(ServiceRegistry::get(BookServiceInterface::class),
                ServiceRegistry::get(AuthorServiceInterface::class))
        );

        ServiceRegistry::set(AuthorController::class,
            new AuthorController(ServiceRegistry::get(AuthorServiceInterface::class))
        );
    }
}
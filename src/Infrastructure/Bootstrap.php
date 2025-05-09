<?php

namespace BookStore\Infrastructure;

use BookStore\Business\Repository\AuthorRepositoryInterface;
use BookStore\Business\Repository\BookRepositoryInterface;
use BookStore\Business\Service\AuthorService;
use BookStore\Business\Service\AuthorServiceInterface;
use BookStore\Business\Service\BookService;
use BookStore\Business\Service\BookServiceInterface;
use BookStore\Data\Repository\DatabaseAuthorRepository;
use BookStore\Data\Repository\DatabaseBookRepository;
use BookStore\Data\Repository\SessionBookRepository;
use BookStore\Data\Repository\SessionAuthorRepository;
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
<?php

namespace BookStore\Infrastructure;

use BookStore\Controller\AuthorController;
use BookStore\Repository\AuthorRepositoryInterface;
use BookStore\Service\AuthorService;

use BookStore\Controller\BookController;
use BookStore\Service\BookService;
use BookStore\Repository\BookRepositoryInterface;

use Exception;

class ServiceRegistry
{
    private static ?Container $container = null;

    /**
     * Returns a container if it exists. If it doesn't, it is created and filled with services
     *
     * @return Container
     */
    public static function getContainer(): Container
    {
        if (self::$container === null) {
            self::$container = new Container();
            $factory = new ServiceFactory();
            self::registerServices($factory);
        }

        return self::$container;
    }


    /**
     * Gets a service with provided name
     *
     * @param string $name
     *
     * @return mixed
     * @throws Exception
     */
    public static function get(string $name): mixed
    {
        return self::getContainer()->get($name);
    }

    /**
     * Creates services and adds them to services array
     *
     * @param ServiceFactory $factory
     *
     * @return void
     */
    private static function registerServices(ServiceFactory $factory): void
    {
        $authorRepository = $factory->createAuthorRepository();
        self::$container->set(AuthorRepositoryInterface::class, $authorRepository);

        $authorService = $factory->createAuthorService($authorRepository);
        self::$container->set(AuthorService::class, $authorService);

        $authorController = $factory->createAuthorController($authorService);
        self::$container->set(AuthorController::class, $authorController);

        $bookRepository = $factory->createBookRepository();
        self::$container->set(BookRepositoryInterface::class, $bookRepository);

        $bookService = $factory->createBookService($bookRepository);
        self::$container->set(BookService::class, $bookService);

        $bookController = $factory->createBookController($bookService);
        self::$container->set(BookController::class, $bookController);
    }
}
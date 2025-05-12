<?php

require_once __DIR__ . "/../vendor/autoload.php";

use BookStore\Bootstrap;
use BookStore\Infrastructure\DI\ServiceRegistry;
use BookStore\Presentation\Controller\AuthorController;
use BookStore\Presentation\Controller\BookController;
use BookStore\Infrastructure\Response\HtmlResponse;
use BookStore\Infrastructure\Response\JsonResponse;

$basePath = '';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$routePath = str_replace($basePath, '', parse_url($requestUri, PHP_URL_PATH));

try {
    Bootstrap::init();

    if (!str_starts_with($routePath, '/api/')) {
        $authorController = ServiceRegistry::get(AuthorController::class);
        $action = $_GET['action'] ?? 'listAuthors';
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        switch ($action) {
            case 'listAuthors':
                $authorController->listAuthors()->view();
                break;
            case 'createAuthor':
                if ($requestMethod === 'POST') {
                    $firstName = $_POST['first_name'] ?? null;
                    $lastName = $_POST['last_name'] ?? null;
                    $authorController->createAuthor($firstName, $lastName)->view();
                } else {
                    $errors = ['firstName' => '', 'lastName' => ''];
                    $authorController->createAuthorPage($errors)->view();
                }
                break;
            case 'editAuthor':
                if ($id !== null) {
                    if ($requestMethod === 'POST') {
                        $firstName = $_POST['first_name'] ?? null;
                        $lastName = $_POST['last_name'] ?? null;
                        $authorController->editAuthor($id, $firstName, $lastName)->view();
                    } else {
                        $errors = ['firstName' => '', 'lastName' => ''];
                        $authorController->editAuthorPage($id, $errors)->view();
                    }
                } else {
                    HtmlResponse::createBadRequest()->view();
                }
                break;
            case 'deleteAuthor':
                if ($id !== null) {
                    if ($requestMethod === 'POST') {
                        $deleteAction = $_POST['action'] ?? 'delete';
                        $authorController->deleteAuthor($id, $deleteAction)->view();
                    } else {
                        $authorController->deleteAuthorPage($id)->view();
                    }
                } else {
                    HtmlResponse::createBadRequest()->view();
                }
                break;
            default:
                HtmlResponse::createNotFound()->view();
        }
        exit;
    }
    $bookController = ServiceRegistry::get(BookController::class);

    // GET /api/books
    if ($requestMethod === 'GET' && $routePath === '/api/books') {
        $authorId = $_GET['authorId'] ?? null;
        $bookController->getBooksByAuthor($authorId)->view();
    } // POST /api/books/create
    elseif ($requestMethod === 'POST' && $routePath === '/api/books/create') {
        $bookController->createBook()->view();
    } // POST /api/books/{id}/edit
    elseif ($requestMethod === 'POST' && preg_match(
            '/^\/api\/books\/(\d+)\/edit$/', $routePath, $matches)
    ) {
        $bookId = (int)$matches[1];
        $bookController->editBook($bookId)->view();
    } // POST /api/books/{id}/delete
    elseif ($requestMethod === 'POST' && preg_match(
            '/^\/api\/books\/(\d+)\/delete$/', $routePath, $matches)
    ) {
        $bookId = (int)$matches[1];
        $bookController->deleteBook($bookId)->view();
    } else {
        JsonResponse::createNotFound()->view();
    }

} catch (Exception $e) {
    error_log("Unhandled Exception: " . $e->getMessage() . "\nStack Trace:\n" . $e->getTraceAsString());

    if (str_starts_with($routePath, '/api/')) {
        JsonResponse::createInternalServerError()->view();
    } else {
        HtmlResponse::createInternalServerError()->view();
    }
}

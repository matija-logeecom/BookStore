<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../vendor/autoload.php";

use BookStore\Controller\AuthorController;
use BookStore\Controller\BookController;
use BookStore\Infrastructure\ServiceRegistry;
use BookStore\Response\HtmlResponse;
use BookStore\Response\JsonResponse;

$basePath = '';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$routePath = str_replace($basePath, '', parse_url($requestUri, PHP_URL_PATH));

try {
    if (!str_starts_with($routePath, '/api/')) {
        $authorController = ServiceRegistry::get(AuthorController::class);
        $action = $_GET['action'] ?? 'listAuthors';
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        switch ($action) {
            case 'listAuthors':
                $authorController->listAuthors();
                break;
            case 'createAuthor':
                $authorController->createAuthor();
                break;
            case 'editAuthor':
                if ($id !== null) {
                    $authorController->editAuthor($id);
                } else {
                    (new HtmlResponse("Error: Author ID is required for editing.", 400))->view();
                }
                break;
            case 'deleteAuthor':
                if ($id !== null) {
                    $authorController->deleteAuthor($id);
                } else {
                    (new HtmlResponse("Error: Author ID is required for deletion.", 400))->view();
                }
                break;
            default:
                (new HtmlResponse("Page not found.", 404))->view();
        }
        exit;
    }
    $bookController = ServiceRegistry::get(BookController::class);

    // GET /api/books
    if ($requestMethod === 'GET' && $routePath === '/api/books') {
        $bookController->getBooksByAuthor();
    }
    // POST /api/books/create
    elseif ($requestMethod === 'POST' && $routePath === '/api/books/create') {
        $bookController->createBook();
    }
    // POST /api/books/{id}/edit
    elseif ($requestMethod === 'POST' && preg_match('/^\/api\/books\/(\d+)\/edit$/', $routePath, $matches)) {
        $bookId = (int)$matches[1];
        $bookController->editBook($bookId);
    }
    // POST /api/books/{id}/delete
    elseif ($requestMethod === 'POST' && preg_match('/^\/api\/books\/(\d+)\/delete$/', $routePath, $matches)) {
        $bookId = (int)$matches[1];
        $bookController->deleteBook($bookId);
    }
    else {
        (new JsonResponse(['error' => 'API endpoint not found.'], 404))->view();
    }

} catch (Exception $e) {
    error_log("Unhandled Exception: " . $e->getMessage() . "\nStack Trace:\n" . $e->getTraceAsString());

    if (str_starts_with($routePath, '/api/')) {
        (new JsonResponse(['error' => 'An internal server error occurred.'], 500))->view();
    } else {
        (new HtmlResponse("<h1>Error</h1><p>An internal server error occurred. Please try again later.</p><pre>" . $e->getMessage() . "</pre>", 500))->view();
    }
}
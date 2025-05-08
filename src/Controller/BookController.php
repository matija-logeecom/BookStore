<?php

namespace BookStore\Controller;

use BookStore\Service\BookService;
use BookStore\Response\JsonResponse;

class BookController
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function getBooksByAuthor(): JsonResponse
    {
        $authorId = $_GET['authorId'] ?? null;
        $books = [];

        if ($authorId !== null) {
            $books = $this->bookService->getBooksByAuthor($authorId);
        }

        return new JsonResponse($books);
    }

    public function createBook(): JsonResponse
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $errors = [];

        $title = $data['title'] ?? '';
        $year = $data['year'] ?? '';
        $authorId = isset($data['author_id']) ? (int)$data['author_id'] : null;

        $newBook = $this->bookService->addBook($title, (string)$year, $authorId, $errors);

        if (!$newBook) {
            return JsonResponse::createBadRequest();
        }

        return new JsonResponse(['success' => true, 'book' => $newBook], 201);
    }

    public function editBook(int $id): JsonResponse
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $errors = [];

        $title = $data['title'] ?? '';
        $year = $data['year'] ?? '';

        $updatedBook = $this->bookService->editBook($id, $title, (string)$year, $errors);

        if (!empty($errors)) {
            return JsonResponse::createBadRequest($errors);
        }

        if (!$this->bookService->getBookById($id)) {
            return JsonResponse::createNotFound();
        }

        // Success
        if ($updatedBook) {
            return new JsonResponse(['success' => true, 'book' => $updatedBook]);
        }

        return JsonResponse::createInternalServerError();
    }

    public function deleteBook(int $id): JsonResponse
    {
        $success = $this->bookService->deleteBook($id);

        if (!$success) {
            return JsonResponse::createNotFound();
        }

        return new JsonResponse(['success' => true, 'message' => 'Book deleted successfully.']);
    }
}
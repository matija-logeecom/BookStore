<?php

namespace BookStore\Controller;

use BookStore\Service\BookService;
use BookStore\Response\HtmlResponse;
use BookStore\Response\JsonResponse;

class BookController
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function getBooksByAuthor(): void
    {
        $authorId = $_GET['authorId'] ?? null;
        $books = [];

        if ($authorId !== null) {
            $books = $this->bookService->getBooksByAuthor($authorId);
        }

        $response = new JsonResponse($books);
        $response->view();
    }

    public function createBook(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $errors = [];

        $title = $data['title'] ?? '';
        $year = $data['year'] ?? '';
        $authorId = isset($data['author_id']) ? (int)$data['author_id'] : null;

        $newBook = $this->bookService->addBook($title, (string)$year, $authorId, $errors);

        if ($newBook) {
            $response = new JsonResponse(['success' => true, 'book' => $newBook], 201); // 201 Created
        } else {
            $response = new JsonResponse(['success' => false, 'errors' => $errors], 400); // 400 Bad Request for validation errors
        }
        $response->view();
    }

    public function editBook(int $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $errors = [];

        $title = $data['title'] ?? '';
        $year = $data['year'] ?? '';

        $updatedBook = $this->bookService->editBook($id, $title, (string)$year, $errors);

        if ($updatedBook) {
            $response = new JsonResponse(['success' => true, 'book' => $updatedBook]);
        } else {
            if (!empty($errors)) {
                $response = new JsonResponse(['success' => false, 'errors' => $errors], 400);
            } elseif (!$this->bookService->getBookById($id)) {
                $response = new JsonResponse(['success' => false, 'errors' => ['general' => 'Book not found.']], 404);
            } else {
                $response = new JsonResponse(['success' => false, 'errors' => ['general' => 'Failed to update book.']], 500);
            }
        }
        $response->view();
    }

    public function deleteBook(int $id): void
    {
        $success = $this->bookService->deleteBook($id);

        if ($success) {
            $response = new JsonResponse(['success' => true, 'message' => 'Book deleted successfully.']);
        } else {
            $response = new JsonResponse(['success' => false, 'message' => 'Failed to delete book or book not found.'], 404);
        }
        $response->view();
    }
}
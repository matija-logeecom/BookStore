<?php

namespace BookStore\Presentation\Controller;

use BookStore\Business\Model\Author\Author;
use BookStore\Business\Model\Book\Book;
use BookStore\Business\Service\Book\BookService;
use BookStore\Infrastructure\Response\JsonResponse;

class BookController
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function getBooksByAuthor(int $authorId): JsonResponse
    {
        $author = new Author($authorId, '');

        $books = $this->bookService->getBooksByAuthor($author);
        if (!isset($books)) {
            return JsonResponse::createNotFound();
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

        $book = new Book(0, $title, $year, $authorId);
        $newBook = $this->bookService->addBook($book, $errors);

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

        $existingBook = $this->bookService->getBookById($id);

        $book = new Book($id, $title, $year, $existingBook->getAuthorId());
        $updatedBook = $this->bookService->editBook($book, $errors);

        if (!empty($errors)) {
            return JsonResponse::createBadRequest();
        }

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
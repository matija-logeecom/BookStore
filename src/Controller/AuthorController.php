<?php

namespace BookStore\Controller;

use BookStore\Response\Response;
use BookStore\Service\AuthorService;
use BookStore\Response\HtmlResponse;
class AuthorController
{
    private AuthorService $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * Shows all authors on page
     *
     * @return Response
     */
    public function listAuthors(): Response
    {
        $authors = $this->authorService->getAuthorList();

        $path = __DIR__ . "/../../public/pages/authors.phtml";
        return new HtmlResponse($path, variables: ["authors" => $authors]);
    }

    /**
     * Adds an author to current session
     *
     * @return Response
     */
    public function createAuthor(): Response
    {
        $errors = ['firstName' => '', 'lastName' => ''];
        $firstName = trim($_POST['first_name']) ?? '';
        $lastName = trim($_POST['last_name']) ?? '';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $path = __DIR__ . "/../../public/pages/create_author.phtml";
            return new HtmlResponse($path, variables: [
                "errors" => $errors,
                'firstName' => $firstName,
                'lastName' => $lastName,
            ]);
        }

        $this->authorService->addAuthor($firstName, $lastName, $errors);

        if (!empty($errors['firstName'] || !empty($errors['lastName']))) {
            $path = __DIR__ . "/../../public/pages/create_author.phtml";
            return new HtmlResponse($path, variables: [
                "errors" => $errors,
                'firstName' => $firstName,
                'lastName' => $lastName,
            ]);
        }

        return new HtmlResponse("", statusCode: 303, headers: ['Location' => 'index.php']);
    }

    /**
     * Changes author name with provided id in current session
     *
     * @param int $id
     *
     * @return Response
     */
    public function editAuthor(int $id): Response
    {
        $errors = ['firstName' => '', 'lastName' => ''];
        $author = $this->authorService->getAuthorById($id);
        $firstName = explode(' ', $author['name'])[0] ?? '';
        $lastName = explode(' ', $author['name'])[1] ?? '';
        if (!$author) {
            return new HtmlResponse("", statusCode: 303, headers: ['Location' => 'index.php']);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $path = __DIR__ . "/../../public/pages/edit_author.phtml";
            return new HtmlResponse($path, variables: [
                'author' => $author,
                'errors' => $errors,
                'firstName' => $firstName,
                'lastName' => $lastName
            ]);
        }

        $this->authorService->editAuthor($id, $firstName, $lastName, $errors);

        if (!empty($errors['firstName'] || !empty($errors['lastName']))) {
            $path = __DIR__ . "/../../public/pages/edit_author.phtml";
            return new HtmlResponse($path, variables: [
                'author' => $author,
                'errors' => $errors,
                'firstName' => $firstName,
                'lastName' => $lastName
            ]);
        }

        return new HtmlResponse("", statusCode: 303, headers: ['Location' => 'index.php']);
    }

    /**
     * Deletes an author with provided id from current session
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteAuthor(int $id): Response
    {
        $fullName = $this->authorService->getAuthorById($id)['name'] ?? '';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $path = __DIR__ . "/../../public/pages/delete_author.phtml";
            return new HtmlResponse($path, variables: ['fullName' => $fullName]);
        }

        if ($_POST['action'] === 'delete') {
            $this->authorService->deleteAuthor($id);
        }

        return new HtmlResponse("", statusCode: 303, headers: ['Location' => 'index.php']);
    }
}
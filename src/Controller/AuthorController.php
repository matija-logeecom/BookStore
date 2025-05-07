<?php

namespace BookStore\Controller;

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
     * @return HtmlResponse
     */
    public function listAuthors(): HtmlResponse
    {
        $authors = $this->authorService->getAuthorList();

        $path = __DIR__ . "/../../public/pages/authors.phtml";
        return HtmlResponse::createResponse($path, variables: ["authors" => $authors]);
    }

    /**
     * Adds an author to current session
     *
     * @return HtmlResponse
     */
    public function createAuthor(): HtmlResponse
    {
        $errors = ['firstName' => '', 'lastName' => ''];
        $firstName = trim($_POST['first_name']) ?? '';
        $lastName = trim($_POST['last_name']) ?? '';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $path = __DIR__ . "/../../public/pages/create_author.phtml";
            return HtmlResponse::createResponse($path, variables: [
                "errors" => $errors,
                'firstName' => $firstName,
                'lastName' => $lastName,
            ]);
        }

        $this->authorService->addAuthor($firstName, $lastName, $errors);

        if (!empty($errors['firstName'] || !empty($errors['lastName']))) {
            $path = __DIR__ . "/../../public/pages/create_author.phtml";
            return HtmlResponse::createResponse($path, variables: [
                "errors" => $errors,
                'firstName' => $firstName,
                'lastName' => $lastName,
            ]);
        }

        return HtmlResponse::createResponse("", 303, ['Location' => 'index.php']);
    }

    /**
     * Changes author name with provided id in current session
     *
     * @param int $id
     *
     * @return HtmlResponse
     */
    public function editAuthor(int $id): HtmlResponse
    {
        $errors = ['firstName' => '', 'lastName' => ''];
        $author = $this->authorService->getAuthorById($id);
        $firstName = explode(' ', $author['name'])[0] ?? '';
        $lastName = explode(' ', $author['name'])[1] ?? '';
        if (!$author) {
            return HtmlResponse::createResponse("", 303, ['Location' => 'index.php']);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $path = __DIR__ . "/../../public/pages/edit_author.phtml";
            return HtmlResponse::createResponse($path, variables: [
                'author' => $author,
                'errors' => $errors,
                'firstName' => $firstName,
                'lastName' => $lastName
            ]);
        }

        $this->authorService->editAuthor($id, $firstName, $lastName, $errors);

        if (!empty($errors['firstName'] || !empty($errors['lastName']))) {
            $path = __DIR__ . "/../../public/pages/edit_author.phtml";
            return HtmlResponse::createResponse($path, variables: [
                'author' => $author,
                'errors' => $errors,
                'firstName' => $firstName,
                'lastName' => $lastName
            ]);
        }

        return HtmlResponse::createResponse("", 303, ['Location' => 'index.php']);

    }

    /**
     * Deletes an author with provided id from current session
     *
     * @param int $id
     *
     * @return HtmlResponse
     */
    public function deleteAuthor(int $id): HtmlResponse
    {
        $fullName = $this->authorService->getAuthorById($id)['name'] ?? '';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $path = __DIR__ . "/../../public/pages/delete_author.phtml";
            return HtmlResponse::createResponse($path, variables: ['fullName' => $fullName]);
        }

        if ($_POST['action'] === 'delete') {
            $this->authorService->deleteAuthor($id);
        }

        return HtmlResponse::createResponse("", 303, ['Location' => 'index.php']);
    }

//    private function renderPage(string $path, array $variables = [], int $statusCode = 200, array $headers = []): void
//    {
//        extract($variables);
//
//        ob_start();
//        if (!empty($path)) {
//            include $path;
//        }
//        $content = ob_get_clean();
//
//        $response = new HtmlResponse($content, $statusCode, $headers);
//        $response->view();
//    }
}
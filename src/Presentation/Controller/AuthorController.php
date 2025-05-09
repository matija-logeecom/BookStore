<?php

namespace BookStore\Presentation\Controller;

use BookStore\Business\Model\Author\Author;
use BookStore\Business\Service\Author\AuthorService;
use BookStore\Presentation\Response\HtmlResponse;
use BookStore\Presentation\Response\Response;

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

        $path = VIEWS_PATH . "/authors.phtml";
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
//            $path = __DIR__ . "/../../../public/pages/create_author.phtml";
            $path = VIEWS_PATH . "/create_author.phtml";
            return new HtmlResponse($path, variables: [
                "errors" => $errors,
                'firstName' => $firstName,
                'lastName' => $lastName,
            ]);
        }

        $author = new Author(0, trim($firstName . ' ' . $lastName));
        $this->authorService->addAuthor($author, $errors);

        if (!empty($errors['firstName'] || !empty($errors['lastName']))) {
            $path = VIEWS_PATH . "/create_author.phtml";
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
        $firstName = $author->getFirstName() ?? '';
        $lastName = $author->getLastName() ?? '';
        if (!$author) {
            return new HtmlResponse("", statusCode: 303, headers: ['Location' => 'index.php']);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $path = VIEWS_PATH . "/edit_author.phtml";
            return new HtmlResponse($path, variables: [
                'author' => $author,
                'errors' => $errors,
                'firstName' => $firstName,
                'lastName' => $lastName
            ]);
        }

        $firstName = trim($_POST['first_name']) ?? '';
        $lastName = trim($_POST['last_name']) ?? '';
        $editedAuthor = new Author($id, $firstName . ' ' . $lastName);
        $this->authorService->editAuthor($editedAuthor, $errors);

        if (!empty($errors['firstName'] || !empty($errors['lastName']))) {
            $path = VIEWS_PATH . "/edit_author.phtml";
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
        $fullName = $this->authorService->getAuthorById($id)->getName() ?? '';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $path = VIEWS_PATH . "/delete_author.phtml";
            return new HtmlResponse($path, variables: ['fullName' => $fullName, 'authorId' => $id]);
        }

        if ($_POST['action'] === 'delete') {
            $this->authorService->deleteAuthor($id);
        }

        return new HtmlResponse("", statusCode: 303, headers: ['Location' => 'index.php']);
    }
}
<?php

namespace BookStore\Presentation\Controller;

use BookStore\Business\Model\Author\Author;
use BookStore\Business\Service\Author\AuthorService;
use BookStore\Infrastructure\Response\HtmlResponse;
use BookStore\Infrastructure\Response\RedirectionResponse;
use BookStore\Infrastructure\Response\Response;

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
    public function createAuthor(string $firstName = null, string $lastName = null): Response
    {
        $errors = ['firstName' => '', 'lastName' => ''];

        // If condition is true, the request is a GET
        if ($firstName === null && $lastName === null) {
            $path = VIEWS_PATH . "/create_author.phtml";
            return new HtmlResponse($path, variables: [
                "errors" => $errors,
                'firstName' => '',
                'lastName' => '',
            ]);
        }

        $trimmedFirstName = trim($firstName ?? '');
        $trimmedLastName = trim($lastName ?? '');

        $author = new Author(0, trim($trimmedFirstName . ' ' . $trimmedLastName));
        $this->authorService->addAuthor($author, $errors);

        if (!empty($errors['firstName'] || !empty($errors['lastName']))) {
            $path = VIEWS_PATH . "/create_author.phtml";
            return new HtmlResponse($path, variables: [
                "errors" => $errors,
                'firstName' => $trimmedFirstName,
                'lastName' => $trimmedLastName,
            ]);
        }

        return new RedirectionResponse('index.php');
    }

    /**
     * Changes author name with provided id in current session
     *
     * @param int $id
     *
     * @return Response
     */
    public function editAuthor(int $id, string $firstName = null, string $lastName = null): Response
    {
        $errors = ['firstName' => '', 'lastName' => ''];
        $author = $this->authorService->getAuthorById($id);
        if (!$author) {
            return HtmlResponse::createNotFound();
        }

        $currentFirstName = $author->getFirstName() ?? '';
        $currentLastName = $author->getLastName() ?? '';

        // If condition is true, the request is a GET
        if ($firstName === null && $lastName === null) {
            $path = VIEWS_PATH . "/edit_author.phtml";
            return new HtmlResponse($path, variables: [
                'author' => $author,
                'errors' => $errors,
                'firstName' => $currentFirstName,
                'lastName' => $currentLastName
            ]);
        }

        $trimmedFirstName = trim($firstName ?? '');
        $trimmedLastName = trim($lastName ?? '');

        $editedAuthor = new Author($id, $trimmedFirstName . ' ' . $trimmedLastName);
        $this->authorService->editAuthor($editedAuthor, $errors);

        if (!empty($errors['firstName'] || !empty($errors['lastName']))) {
            $path = VIEWS_PATH . "/edit_author.phtml";
            return new HtmlResponse($path, variables: [
                'author' => $author,
                'errors' => $errors,
                'firstName' => $trimmedFirstName,
                'lastName' => $trimmedLastName
            ]);
        }

        return new RedirectionResponse('index.php', 303);
    }

    /**
     * Deletes an author with provided id from current session
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteAuthor(int $id, string $confirmationAction = null): Response
    {
        $author = $this->authorService->getAuthorById($id);
        if (!$author) {
            return new RedirectionResponse('index.php');
        }

        $fullName = $author->getName() ?? '';
        if ($confirmationAction === null) {
            $path = VIEWS_PATH . "/delete_author.phtml";
            return new HtmlResponse($path, variables: ['fullName' => $fullName, 'authorId' => $id]);
        }

        if ($confirmationAction === 'delete') {
            $this->authorService->deleteAuthor($id);
        }

        return new RedirectionResponse('index.php', 303);    }
}
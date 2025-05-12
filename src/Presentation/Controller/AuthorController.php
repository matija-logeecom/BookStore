<?php

namespace BookStore\Presentation\Controller;

use BookStore\Business\Model\Author\Author;
use BookStore\Business\Service\Author\AuthorService;
use BookStore\Infrastructure\Response\HtmlResponse;
use BookStore\Infrastructure\Response\RedirectionResponse;
use BookStore\Infrastructure\Response\Response;

/*
 * Class for handling HTTP requests
 */

class AuthorController
{
    private AuthorService $authorService;

    /**
     * Constructor for Author Service
     *
     * @param AuthorService $authorService
     */
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
     * Handles GET creation requests
     *
     * @param array $errors
     * @return Response
     */

    public function createAuthorPage(array $errors): Response
    {
        $path = VIEWS_PATH . "/create_author.phtml";
        return new HtmlResponse($path, variables: [
            "errors" => $errors,
            'firstName' => '',
            'lastName' => '',
        ]);
    }

    /**
     *  Handles POST creation requests
     *
     * @param string|null $firstName
     * @param string|null $lastName
     *
     * @return Response
     */
    public function createAuthor(string $firstName = null, string $lastName = null): Response
    {
        $errors = ['firstName' => '', 'lastName' => ''];

        $trimmedFirstName = trim($firstName ?? '');
        $trimmedLastName = trim($lastName ?? '');

        $author = new Author(0, trim($trimmedFirstName . ' ' . $trimmedLastName));
        $this->authorService->addAuthor($author, $errors);

        if (!empty($errors['firstName'] || !empty($errors['lastName']))) {
            return $this->createAuthorPage($errors);
        }

        return new RedirectionResponse('index.php');
    }

    /**
     * Handles GET editing requests
     *
     * @param int $id
     * @param array $errors
     *
     * @return Response
     */
    public function editAuthorPage(int $id, array $errors): Response
    {
        $author = $this->authorService->getAuthorById($id);
        if (!$author) {
            return HtmlResponse::createInternalServerError();
        }

        $currentFirstName = $author->getFirstName();
        $currentLastName = $author->getLastName();

        $path = VIEWS_PATH . "/edit_author.phtml";
        return new HtmlResponse($path, variables: [
            'author' => $author,
            'errors' => $errors,
            'firstName' => $currentFirstName,
            'lastName' => $currentLastName
        ]);
    }

    /**
     * Handles POST editing requests
     *
     * @param int $id
     * @param string|null $firstName
     * @param string|null $lastName
     *
     * @return Response
     */
    public function editAuthor(int $id, string $firstName = null, string $lastName = null): Response
    {
        $errors = ['firstName' => '', 'lastName' => '', 'exists' => ''];

        $trimmedFirstName = trim($firstName ?? '');
        $trimmedLastName = trim($lastName ?? '');

        $editedAuthor = new Author($id, $trimmedFirstName . ' ' . $trimmedLastName);
        $success = $this->authorService->editAuthor($editedAuthor, $errors);

        if (!$success) {
            return HtmlResponse::createInternalServerError();
        }

        if (!empty($errors['firstName']) || !empty($errors['lastName']) || !empty($errors['exists'])) {
            return $this->editAuthorPage($id, $errors);
        }

        return new RedirectionResponse('index.php', 303);
    }

    /**
     * Handles GET deletion requests
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteAuthorPage(int $id): Response
    {
        $author = $this->authorService->getAuthorById($id);
        if (!$author) {
            return HtmlResponse::createInternalServerError();
        }

        $fullName = $author->getName() ?? '';

        $path = VIEWS_PATH . "/delete_author.phtml";
        return new HtmlResponse($path, variables: ['fullName' => $fullName, 'authorId' => $id]);
    }

    /**
     * Handles POST deletion requests
     *
     * @param int $id
     * @param string $action
     *
     * @return Response
     */
    public function deleteAuthor(int $id, string $action): Response
    {
        if ($action === 'delete') {
            $success = $this->authorService->deleteAuthor($id);
            if (!$success) {
                return HtmlResponse::createInternalServerError();
            }
        }

        return new RedirectionResponse('index.php', 303);
    }
}

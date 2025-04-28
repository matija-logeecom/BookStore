<?php
require_once __DIR__ . "/../service/AuthorService.php";

class AuthorController
{
    private authorService $authorService;

    public function __construct()
    {
        session_start();
        $this->authorService = new authorService();
    }

    /**
     * @return void
     */
    public function listAuthors(): void
    {
        $authors = $this->authorService->getAuthorList();
        include __DIR__ . "/../../public/pages/authors.phtml";
    }

    /**
     * @return void
     */
    public function createAuthor(): void
    {
        $errors = ['firstName' => '', 'lastName' => ''];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include __DIR__ . "/../../public/pages/create_author.phtml";
            return;
        }

        $firstName = trim($_POST['first_name']) ?? '';
        $lastName = trim($_POST['last_name']) ?? '';
        $this->authorService->addAuthor($firstName, $lastName, $errors);

        if (!empty($errors['firstName'] || !empty($errors['lastName']))) {
            include __DIR__ . "/../../public/pages/create_author.phtml";
            return;
        }

        header('Location: index.php');
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function editAuthor(int $id): void
    {
        $errors = ['firstName' => '', 'lastName' => ''];
        $firstName = $lastName = '';
        $author = $this->authorService->getAuthorById($id);
        if (!$author) {
            header('Location: index.php');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include __DIR__ . "/../../public/pages/edit_author.phtml";
            return;
        }

        $firstName = trim($_POST['first_name']) ?? '';
        $lastName = trim($_POST['last_name']) ?? '';
        $this->authorService->editAuthor($id, $firstName, $lastName, $errors);

        if (!empty($errors['firstName'] || !empty($errors['lastName']))) {
            include __DIR__ . "/../../public/pages/edit_author.phtml";
            return;
        }

        header('Location: index.php');

    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function deleteAuthor(int $id): void
    {
        $fullName = $this->authorService->getAuthorById($id)['name'] ?? '';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include __DIR__ . "/../../public/pages/delete_author.phtml";
            return;
        }
        if ($_POST['action'] === 'delete') {
            $this->authorService->deleteAuthor($id);
        }

        header('Location: index.php');
    }
}
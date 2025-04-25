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

    public function listAuthors(): void {
        $authors = $this->authorService->getAuthorList();
        include __DIR__ . "/../../public/authors.phtml";
    }

    public function createAuthor(): void {
        $errors = ['firstName' => '', 'lastName' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name']) ?? '';
            $lastName = trim($_POST['last_name']) ?? '';

            if ($this->authorService->validateName($firstName, $lastName, $errors)) {
                $this->authorService->addAuthor($firstName, $lastName);
                header('Location: index.php?action=listAuthors');
                exit;
            }
        }

        include __DIR__ . "/../../public/create_author.phtml";
    }

    public function editAuthor(int $id): void {
        $errors = ['firstName' => '', 'lastName' => ''];
        $firstName = $lastName = '';
        $author = $this->authorService->getAuthorById($id);
        if(!$author) {
            header('Location: index.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name']) ?? '';
            $lastName = trim($_POST['last_name']) ?? '';

            if ($this->authorService->validateName($firstName, $lastName, $errors)) {
                $this->authorService->editAuthor($id, $firstName, $lastName);

                header('Location: index.php');
            }
        }

        include __DIR__ . "/../../public/edit_author.phtml";
    }

    public function deleteAuthor(int $id): void {
        $fullName = $this->authorService->getAuthorById($id)['name'] ?? '';
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['action'] === 'delete') {
                $this->authorService->deleteAuthor($id);
            }
            header('Location: index.php');
        }

        include __DIR__ . "/../../public/delete_author.phtml";
    }
}
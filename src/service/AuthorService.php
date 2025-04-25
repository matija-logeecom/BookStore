<?php
require_once __DIR__ . "/../repository/AuthorRepository.php";
class AuthorService
{
    private AuthorRepository $repo;

    public function __construct()
    {
        $this->repo = new AuthorRepository();
    }

    public function getAuthorList(): array {
        return $this->repo->getAll();
    }

    public function addAuthor($firstName, $lastName): void {
        $fullName = $firstName . ' ' . $lastName;
        $this->repo->addAuthor($fullName);
    }

    public function editAuthor($authorId, $firstName, $lastName): void {
        $fullName = $firstName . ' ' . $lastName;
        $this->repo->editAuthor($authorId, $fullName);
    }

    public function deleteAuthor(int $authorId): void {
        $this->repo->deleteAuthor($authorId);
    }
    public function validateName($firstName, $lastName, &$errors): bool {
        if ($firstName === '') {
            $errors['firstName'] = "* This field is required";
        } elseif (strlen($firstName) > 100) {
            $errors['firstName'] = "First name must be <= 100 characters";
        }

        if ($lastName === '') {
            $errors['lastName'] = "* This field is required";
        } elseif (strlen($lastName) > 100) {
            $errors['lastName'] = "Last name must be <= 100 characters";
        }

        return ($errors['firstName'] === '' && $errors['lastName'] === '');
    }

    public function getAuthorById($id): ?array {
        return $this->repo->getAuthorById($id);
    }
}
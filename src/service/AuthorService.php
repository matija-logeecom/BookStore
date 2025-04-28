<?php
require_once __DIR__ . "/../repository/AuthorRepository.php";

class AuthorService
{
    private AuthorRepository $repository;

    public function __construct()
    {
        $this->repository = new AuthorRepository();
    }

    /**
     * @return array
     */
    public function getAuthorList(): array
    {
        return $this->repository->getAll();
    }


    /**
     * @param $firstName
     * @param $lastName
     * @param $errors
     *
     * @return void
     */
    public function addAuthor($firstName, $lastName, &$errors): void
    {
        if (!$this->validateName($firstName, $lastName, $errors)) {
            return;
        }

        $fullName = $firstName . ' ' . $lastName;
        $this->repository->addAuthor($fullName);
    }

    /**
     * @param $authorId
     * @param $firstName
     * @param $lastName
     * @param $errors
     *
     * @return void
     */
    public function editAuthor($authorId, $firstName, $lastName, &$errors): void
    {
        if (!$this->validateName($firstName, $lastName, $errors)) {
            return;
        }

        $fullName = $firstName . ' ' . $lastName;
        $this->repository->editAuthor($authorId, $fullName);
    }

    /**
     * @param int $authorId
     *
     * @return void
     */
    public function deleteAuthor(int $authorId): void
    {
        $this->repository->deleteAuthor($authorId);
    }


    /**
     * @param $id
     *
     * @return array|null
     */
    public function getAuthorById($id): ?array
    {
        return $this->repository->getAuthorById($id);
    }

    /**
     * @param $firstName
     * @param $lastName
     * @param $errors
     *
     * @return bool
     */
    private function validateName($firstName, $lastName, &$errors): bool
    {
        if ($firstName === '') {
            $errors['firstName'] = "* This field is required";
        }
        if (strlen($firstName) > 100) {
            $errors['firstName'] = "First name must be <= 100 characters";
        }

        if ($lastName === '') {
            $errors['lastName'] = "* This field is required";
        }
        if (strlen($lastName) > 100) {
            $errors['lastName'] = "Last name must be <= 100 characters";
        }

        return (empty($errors['firstName']) && empty($errors['lastName']));
    }
}
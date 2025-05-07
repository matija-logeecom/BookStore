<?php

namespace BookStore\Service;

use BookStore\Repository\AuthorRepositoryInterface;
use BookStore\Repository\BookRepositoryInterface;

class AuthorService
{
    private AuthorRepositoryInterface $authorRepository;
    private BookRepositoryInterface $bookRepository;

    public function __construct(AuthorRepositoryInterface $authorRepository, BookRepositoryInterface $bookRepository)
    {
        $this->authorRepository = $authorRepository;
        $this->bookRepository = $bookRepository;
    }

    /**
     * Retrieves a list of all authors in current session
     *
     * @return array
     */
    public function getAuthorList(): array
    {
        $authors = array_map(fn($author) => $author + ['books' => 0], $this->authorRepository->getAll());
        foreach ($authors as &$author) {
           $author['books'] = $this->countBooks($author);
        }

        return $authors;
    }

    /**
     * Adds an author with provided name to current session
     *
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
        $this->authorRepository->addAuthor($fullName);
    }

    /**
     * Changes name of author with provided id to provided name
     *
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
        $this->authorRepository->editAuthor($authorId, $fullName);
    }

    /**
     * Deletes an author with provided id from current session
     *
     * @param int $authorId
     *
     * @return void
     */
    public function deleteAuthor(int $authorId): void
    {
        $this->authorRepository->deleteAuthor($authorId);

        $books = $this->bookRepository->getBooksByAuthorId($authorId);
        foreach ($books as $book) {
            $this->bookRepository->deleteBook($book['id']);
        }
    }


    /**
     * Returns an author with provided id
     *
     * @param $id
     *
     * @return array|null
     */
    public function getAuthorById($id): ?array
    {
        return $this->authorRepository->getAuthorById($id);
    }

    /**
     * Checks whether firstName and lastName are correct and changes errors array accordingly
     *
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

    private function countBooks(mixed $author): int
    {
        $books = $this->bookRepository->getBooksByAuthorId($author['id']);

        return count($books);
    }
}
<?php

namespace BookStore\Business\Service\Author;

use BookStore\Business\Model\Author\Author;
use BookStore\Business\Repository\AuthorRepositoryInterface;
use BookStore\Business\Repository\BookRepositoryInterface;

/*
 * Class for handling service logic for Authors
 */

class AuthorService implements AuthorServiceInterface
{
    private AuthorRepositoryInterface $authorRepository;
    private BookRepositoryInterface $bookRepository;

    /**
     * Constructs Author Service instance
     *
     * @param AuthorRepositoryInterface $authorRepository
     * @param BookRepositoryInterface $bookRepository
     */
    public function __construct(AuthorRepositoryInterface $authorRepository, BookRepositoryInterface $bookRepository)
    {
        $this->authorRepository = $authorRepository;
        $this->bookRepository = $bookRepository;
    }

    /**
     * @inheritDoc
     */
    public function getAuthorList(): array
    {
        return $this->authorRepository->getAll();
    }

    /**
     * @inheritDoc
     */
    public function addAuthor(Author $author, array &$errors): bool
    {
        if (!$this->isAuthorValid($author, $errors)) {
            return false;
        }

        return $this->authorRepository->addAuthor($author);
    }

    /**
     * @inheritDoc
     */
    public function editAuthor(Author $author, &$errors): bool
    {
        if (!$this->isAuthorValid($author, $errors) || !$this->isAuthorPresent($author->getId())) {
            return false;
        }

        return $this->authorRepository->editAuthor($author);
    }

    /**
     * @inheritDoc
     */
    public function deleteAuthor(int $authorId): bool
    {
        $books = $this->bookRepository->getBooksByAuthorId($authorId);
        if (!$this->isAuthorPresent($authorId)) {
            return false;
        }
        foreach ($books as $book) {
            $deleted = $this->bookRepository->deleteBook($book->getId());
            if (!$deleted) {
                return false;
            }
        }

        return $this->authorRepository->deleteAuthor($authorId);
    }

    /**
     * @inheritDoc
     */
    public function getAuthorById(int $id): ?Author
    {
        return $this->authorRepository->getAuthorById($id);
    }

    /**
     * Checks whether firstName and lastName are correct and changes errors array accordingly
     *
     * @param Author $author
     * @param $errors
     *
     * @return bool
     */
    private function isAuthorValid(Author $author, &$errors): bool
    {
        $firstName = $author->getFirstName();
        $lastName = $author->getLastName();

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

    /**
     * Checks if author with provided id exists
     *
     * @param int $authorId
     *
     * @return bool
     */
    private function isAuthorPresent(int $authorId): bool
    {
        $authorRetrieved = $this->authorRepository->getAuthorById($authorId);
        return (bool)$authorRetrieved;
    }
}

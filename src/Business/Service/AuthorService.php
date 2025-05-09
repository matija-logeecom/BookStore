<?php

namespace BookStore\Business\Service;

use BookStore\Business\Repository\AuthorRepositoryInterface;
use BookStore\Business\Repository\BookRepositoryInterface;
use BookStore\Business\Model\Author;

class AuthorService implements AuthorServiceInterface
{
    private AuthorRepositoryInterface $authorRepository;
    private BookRepositoryInterface $bookRepository;

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
    public function addAuthor(Author $author, array &$errors): void
    {
        if (!$this->validateName($author, $errors)) {
            return;
        }

        $this->authorRepository->addAuthor($author);
    }

    /**
     * @inheritDoc
     */
    public function editAuthor(Author $author, &$errors): void
    {
        if (!$this->validateName($author, $errors)) {
            return;
        }

        $this->authorRepository->editAuthor($author);
    }

    /**
     * @inheritDoc
     */
    public function deleteAuthor(int $authorId): void
    {
        $books = $this->bookRepository->getBooksByAuthorId($authorId);
        foreach ($books as $book) {
            $this->bookRepository->deleteBook($book->getId());
        }

        $this->authorRepository->deleteAuthor($authorId);
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
    private function validateName(Author $author, &$errors): bool
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
}
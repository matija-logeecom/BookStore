<?php

namespace BookStore\Data\Repository;

use BookStore\Business\Repository\AuthorRepositoryInterface;
use BookStore\Business\Model\Author;
class SessionAuthorRepository implements AuthorRepositoryInterface
{
    public function __construct()
    {
        $_SESSION['authors'] = $_SESSION['authors'] ?? [];
        $_SESSION['currentId'] = $_SESSION['currentId'] ?? 1;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        $authors = $_SESSION['authors'];

        $authorModels = [];
        foreach ($authors as $author) {
            $authorModels[] = new Author($author['id'], $author['name'], $this->bookCount($author['id']));
        }

        return $authorModels;
    }

    /**
     * @inheritDoc
     */
    public function addAuthor(Author $author): void
    {
        $_SESSION['authors'][] = ['id' => $_SESSION['currentId']++, 'name' => $author->getName(), 'books' => 0];
    }

    /**
     * @inheritDoc
     */
    public function editAuthor(Author $author): void
    {
        $authorIndex = $this->getAuthorIndex($author->getId());
        $_SESSION['authors'][$authorIndex]['name'] = $author->getName();
    }

    /**
     * @inheritDoc
     */
    public function deleteAuthor(int $authorId): void
    {
        $_SESSION['authors'] = array_filter($_SESSION['authors'], fn($a) => $a['id'] !== $authorId);
    }

    /**
     * @inheritDoc
     */
    public function getAuthorById(int $authorId): ?Author
    {
        $authors = $this->getAll();

        return current(array_filter($authors, fn($a) => $a->getId() === $authorId));
    }

    /**
     * Retrieves index of author with provided id from current session
     *
     * @param $authorId
     *
     * @return int|null
     */
    private function getAuthorIndex($authorId): ?int
    {
        $authors = $_SESSION['authors'];
        foreach ($authors as $index => $author) {
            if ($author['id'] === $authorId) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * Returns number of books
     *
     * @param int $authorId
     *
     * @return int
     */
    private function bookCount(int $authorId): int
    {
        $bookCounter = 0;
        foreach ($_SESSION['books'] as $book) {
            if ($book['author_id'] === $authorId) {
                $bookCounter++;
            }
        }

        return $bookCounter;
    }
}
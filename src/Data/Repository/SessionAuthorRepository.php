<?php

namespace BookStore\Data\Repository;

use BookStore\Business\Repository\AuthorRepositoryInterface;

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
        return $_SESSION['authors'];
    }

    /**
     * @inheritDoc
     */
    public function addAuthor(string $fullName): void
    {
        $_SESSION['authors'][] = ['id' => $_SESSION['currentId']++, 'name' => $fullName, 'books' => 0];
    }

    /**
     * @inheritDoc
     */
    public function editAuthor(int $authorId, string $fullName): void
    {
        $authorIndex = $this->getAuthorIndex($authorId);
        $_SESSION['authors'][$authorIndex]['name'] = $fullName;
    }

    /**
     * @inheritDoc
     */
    public function deleteAuthor(int $authorId): void
    {
        $_SESSION['authors'] = array_filter($_SESSION['authors'], fn($a) => $a['id'] !== $authorId);
    }

    /**
     * Retrieves an author with provided id from current session
     *
     * @param int $authorId
     *
     * @return array|null
     */
    public function getAuthorById(int $authorId): ?array
    {
        $authors = $this->getAll();

        return current(array_filter($authors, fn($a) => $a['id'] === $authorId));
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
}
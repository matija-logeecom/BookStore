<?php

namespace BookStore\Data\Repository\Author;

use BookStore\Business\Model\Author\Author;
use BookStore\Business\Repository\AuthorRepositoryInterface;
use BookStore\Infrastructure\Session\SessionManager;

class SessionAuthorRepository implements AuthorRepositoryInterface
{
    public function __construct()
    {
        if (!SessionManager::getInstance()->has('authors')) {
            SessionManager::getInstance()->set('authors', []);
        }

        if (!SessionManager::getInstance()->has('currentId')) {
            SessionManager::getInstance()->set('currentId', 1);
        }
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        $authors = SessionManager::getInstance()->get('authors');

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
        $authors = SessionManager::getInstance()->get('authors');
        $currentId = SessionManager::getInstance()->get('currentId');

        $newAuthor = [
            'id' => $currentId,
            'name' => $author->getName(),
        ];

        $authors[] = $newAuthor;
        SessionManager::getInstance()->set('authors', $authors);
        SessionManager::getInstance()->set('currentId', $currentId + 1);
    }

    /**
     * @inheritDoc
     */
    public function editAuthor(Author $author): void
    {
        $authorIndex = $this->getAuthorIndex($author->getId());
        $authors = SessionManager::getInstance()->get('authors');

        $authors[$authorIndex]['name'] = $author->getName();
        SessionManager::getInstance()->set('authors', $authors);
    }

    /**
     * @inheritDoc
     */
    public function deleteAuthor(int $authorId): void
    {
        $filteredAuthors = array_filter(
            SessionManager::getInstance()->get('authors'), fn($a) => $a['id'] !== $authorId
        );
        SessionManager::getInstance()->set('authors', $filteredAuthors);
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
        $authors = SessionManager::getInstance()->get('authors');
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
        foreach (SessionManager::getInstance()->get('books') as $book) {
            if ($book['author_id'] === $authorId) {
                $bookCounter++;
            }
        }

        return $bookCounter;
    }
}
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
    public function addAuthor(Author $author): bool
    {
        $authors = SessionManager::getInstance()->get('authors');
        $currentId = SessionManager::getInstance()->get('currentId');

        if (!$currentId) {
            return false;
        }

        $newAuthor = [
            'id' => $currentId,
            'name' => $author->getName(),
        ];

        $authors[$currentId] = $newAuthor;
        SessionManager::getInstance()->set('authors', $authors);
        SessionManager::getInstance()->set('currentId', $currentId + 1);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function editAuthor(Author $author): bool
    {
//        $authorIndex = $this->getAuthorIndex($author->getId());
        $authors = SessionManager::getInstance()->get('authors');

        if (!isset($authors)) {
            return false;
        }

        $authors[$author->getId()]['name'] = $author->getName();
        SessionManager::getInstance()->set('authors', $authors);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteAuthor(int $authorId): bool
    {
        $authors = SessionManager::getInstance()->get('authors');
        $filteredAuthors = array_filter(
            $authors, fn($a) => $a['id'] !== $authorId
        );

        if (count($filteredAuthors) === count($authors)) {
            return false;
        }

        SessionManager::getInstance()->set('authors', $filteredAuthors);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getAuthorById(int $authorId): ?Author
    {
        $authorData = SessionManager::getInstance()->get('authors')[$authorId] ?? null;
        if ($authorData !== null) {
            $bookCount = $this->bookCount($authorId);
            return new Author($authorId, $authorData['name'], $bookCount);
        }

        return null;
    }

    /**
     * Retrieves index of author with provided id from current session
     *
     * @param $authorId
     *
     * @return int|null
     */
//    private function getAuthorIndex($authorId): ?int
//    {
//        $authors = SessionManager::getInstance()->get('authors');
//        foreach ($authors as $index => $author) {
//            if ($author['id'] === $authorId) {
//                return $index;
//            }
//        }
//
//        return -1;
//    }

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
<?php

class AuthorRepository
{
    public function __construct()
    {
        $_SESSION['authors'] = $_SESSION['authors'] ?? [];
        $_SESSION['currentId'] = $_SESSION['currentId'] ?? 1;
    }

    /**
     * Retrieves a list of all authors in current session
     *
     * @return array
     */
    public function getAll(): array
    {
        return $_SESSION['authors'];
    }

    /**
     * Adds an author with provided name to current session
     *
     * @param $fullName
     *
     * @return void
     */
    public function addAuthor($fullName): void
    {
        $_SESSION['authors'][] = ['id' => $_SESSION['currentId']++, 'name' => $fullName, 'books' => 0];
    }

    /**
     * Changes name of author with provided id to provided name
     *
     * @param $authorId
     * @param $fullName
     *
     * @return void
     */
    public function editAuthor($authorId, $fullName): void
    {
        $authorIndex = $this->getAuthorIndex($authorId);
        $_SESSION['authors'][$authorIndex]['name'] = $fullName;
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
        return current(array_filter($_SESSION['authors'], fn($a) => $a['id'] === $authorId));
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
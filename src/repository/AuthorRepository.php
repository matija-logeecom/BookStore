<?php

class AuthorRepository
{
    public function __construct()
    {
        $_SESSION['authors'] = $_SESSION['authors'] ?? [];
        $_SESSION['currentId'] = $_SESSION['currentId'] ?? 1;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $_SESSION['authors'];
    }

    /**
     * @param $fullName
     *
     * @return void
     */
    public function addAuthor($fullName): void
    {
        $_SESSION['authors'][] = ['id' => $_SESSION['currentId']++, 'name' => $fullName, 'books' => 0];
    }

    /**
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
     * @param int $authorId
     *
     * @return void
     */
    public function deleteAuthor(int $authorId): void
    {
        $_SESSION['authors'] = array_filter($_SESSION['authors'], fn($a) => $a['id'] !== $authorId);
    }


    /**
     * @param int $authorId
     *
     * @return array|null
     */
    public function getAuthorById(int $authorId): ?array
    {
        return current(array_filter($_SESSION['authors'], fn($a) => $a['id'] === $authorId));
    }

    /**
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
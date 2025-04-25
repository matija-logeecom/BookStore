<?php
class AuthorRepository
{
    public function __construct()
    {
        $_SESSION['authors'] = $_SESSION['authors'] ?? [];
        $_SESSION['currentId'] = $_SESSION['currentId'] ?? 1;
    }

    public function getAll(): array {
        return $_SESSION['authors'];
    }

    public function addAuthor($fullName): void {
        $_SESSION['authors'][] = ['id' => $_SESSION['currentId']++, 'name' => $fullName, 'books' => 0];
    }

    public function editAuthor($authorId, $fullName): void {
        $authorIndex = $this->getAuthorIndex($authorId);
        $_SESSION['authors'][$authorIndex]['name'] = $fullName;
    }

    public function deleteAuthor(int $authorId): void {
        $_SESSION['authors'] = array_filter($_SESSION['authors'], fn($a) => $a['id'] !== $authorId);
    }

    public function getAuthorById($authorId): ?array {
        return current(array_filter($_SESSION['authors'], fn($a) => $a['id'] == $authorId));
    }

    public function getAuthorIndex($authorId): ?int {
        $authors = $_SESSION['authors'];
        foreach ($authors as $index => $author) {
            if ($author['id'] === $authorId) {
                return $index;
            }
        }

        return -1;
    }
}
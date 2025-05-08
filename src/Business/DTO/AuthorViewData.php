<?php

namespace BookStore\Business\DTO;

use BookStore\Business\Model\Author;

class AuthorViewData
{
    public int $id;
    public string $name;
    public int $bookCount;

    public function __construct(Author $author, int $bookCount)
    {
        $this->id = $author->getId();
        $this->name = $author->getName();
        $this->bookCount = $bookCount;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'books' => $this->bookCount
        ];
    }
}
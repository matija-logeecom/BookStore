<?php

namespace BookStore\Business\Model;

class Author
{
    private int $id;
    private string $name;
    private int $bookCount = 0;

    public function __construct(int $id, string $name, int $bookCount = 0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->bookCount = $bookCount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFirstName(): string
    {
        return explode(' ', $this->name)[0];
    }

    public function getLastName(): string
    {
        return explode(' ', $this->name)[1];
    }

    public function getBookCount(): int
    {
        return $this->bookCount;
    }
}
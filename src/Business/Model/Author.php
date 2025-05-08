<?php

namespace BookStore\Business\Model;

class Author
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
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
        return $this->name.explode(' ', $this->name)[0];
    }

    public function getLastName(): string
    {
        return $this->name.explode(' ', $this->name)[1];
    }
}
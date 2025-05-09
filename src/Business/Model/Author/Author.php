<?php

namespace BookStore\Business\Model\Author;

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

    /**
     * Getter for ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Getter for full name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Getter for first name
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return explode(' ', $this->name)[0];
    }

    /**
     * Getter for last name
     *
     * @return string
     */
    public function getLastName(): string
    {
        return explode(' ', $this->name)[1];
    }

    /**
     * Getter for book count
     *
     * @return int
     */
    public function getBookCount(): int
    {
        return $this->bookCount;
    }
}
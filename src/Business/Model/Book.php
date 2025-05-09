<?php

namespace BookStore\Business\Model;

use JsonSerializable;

class Book implements JsonSerializable
{
    private int $id;
    private string $title;
    private int $year;
    private int $authorId;

    public function __construct(int $id, string $title, int $year, int $authorId)
    {
        $this->id = $id;
        $this->title = trim($title);
        $this->year = $year;
        $this->authorId = $authorId;
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
     * Getter for title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Getter for year
     *
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * Getter for author id
     *
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * Converts object into array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'year' => $this->year,
            'authorId' => $this->authorId
        ];
    }

    /**
     * Defines how the object is serialized
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'year' => $this->year,
            'authorId' => $this->authorId,
        ];
    }
}
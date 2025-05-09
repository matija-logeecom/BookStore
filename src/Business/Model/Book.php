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

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'year' => $this->year,
            'authorId' => $this->authorId
        ];
    }

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
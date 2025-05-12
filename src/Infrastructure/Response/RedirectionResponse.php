<?php

namespace BookStore\Infrastructure\Response;

use BookStore\Infrastructure\Response\Response;

class RedirectionResponse extends Response
{
    private string $url;

    public function __construct(string $url, int $statusCode = 303, array $headers = [])
    {
        $finalHeaders = array_merge(['Location' => $url], $headers);
        parent::__construct('', $statusCode, $finalHeaders);
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @inheritDoc
     */
    public function view(): void
    {
        parent::view();
    }

    /**
     * @inheritDoc
     */
    static public function createNotFound(string $message = "Page not found."): \BookStore\Infrastructure\Response\Response
    {
        return new self('index.php', 404);
    }

    /**
     * @inheritDoc
     */
    public static function createBadRequest(string $message = "Bad Request."): \BookStore\Infrastructure\Response\Response
    {
        return new self('index.php', 400);
    }

    /**
     * @inheritDoc
     */
    public static function createInternalServerError(string $message = "An internal server error occurred."): \BookStore\Infrastructure\Response\Response
    {
        return new self('index.php', 500);
    }
}
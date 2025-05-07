<?php

namespace BookStore\Response;

abstract class Response
{
    protected mixed $body;
    protected array $headers = [];
    protected int $statusCode = 200;

    public function __construct(mixed $body, int $statusCode = 200, array $headers = [])
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->statusCode = $statusCode;
    }

    public function getBody(): mixed
    {
        return $this->body;
    }

    public function setBody(mixed $body): void
    {
        $this->body = $body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    protected function sendHeaders(): void
    {
        if (headers_sent()) {
            error_log("Headers already sent");

            return;
        }

        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
    }

    abstract public function view(): void;

    abstract static public function createNotFound(string $message = "Page not found."): self;

    abstract public static function createBadRequest(string $message = "Bad Request."): self;

    abstract public static function createInternalServerError(string $message =
                                                              "An internal server error occurred."): self;
}
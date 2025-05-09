<?php

namespace BookStore\Presentation\Response;

class JsonResponse extends Response
{
    public function __construct(mixed $data, int $statusCode = 200, array $headers = [])
    {
        parent::__construct($data, $statusCode, $headers);

        if (!isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = 'application/json';
        }
    }

    /**
     * @inheritDoc
     */
    public function view(): void
    {
        $this->sendHeaders();
        $this->sendStatusCode();
        echo json_encode($this->body);
    }

    /**
     * @inheritDoc
     */
    public static function createNotFound(string $message = "Page not found."): self
    {
        return new self(['error' => $message], 404);
    }

    /**
     * @inheritDoc
     */
    public static function createBadRequest(string $message = "Bad Request."): self
    {
        return new self(['error' => $message], 400);
    }

    /**
     * @inheritDoc
     */
    public static function createInternalServerError(string $message = "Internal server error."): self
    {
        return new self(['error' => $message], 500);
    }
}
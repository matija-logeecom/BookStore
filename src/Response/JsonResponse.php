<?php

namespace BookStore\Response;

class JsonResponse extends Response
{
    public function __construct(mixed $data, int $statusCode = 200, array $headers = [])
    {
        parent::__construct($data, $statusCode, $headers);

        if (!isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = 'application/json';
        }
    }

    public function view(): void
    {
        $this->sendHeaders();
        echo json_encode($this->body);
    }
}
<?php

namespace BookStore\Response;

class HtmlResponse extends Response
{
    public function __construct(string $html, int $statusCode = 200, array $headers = [])
    {
        parent::__construct($html, $statusCode, $headers);

        if (!isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = 'text/html; charset=utf-8';
        }
    }

    public static function createResponse(string $path, int $statusCode = 200, array $headers = [],
                                          array $variables = []) : HtmlResponse
    {
        extract($variables);

        ob_start();
        if (!empty($path)) {
            include $path;
        }
        $html = ob_get_clean();

        return new HtmlResponse($html, $statusCode, $headers);
    }

    public function view(): void
    {
        $this->sendHeaders();
        echo $this->body;
    }
}
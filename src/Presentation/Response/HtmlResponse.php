<?php

namespace BookStore\Presentation\Response;

class HtmlResponse extends Response
{
    private array $variables;

    private string $path;

    public function __construct(string $html, array $variables = [], int $statusCode = 200, array $headers = [])
    {
        parent::__construct($html, $statusCode, $headers);

        if (!isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = 'text/html; charset=utf-8';
        }

        $this->variables = $variables;
        $this->path = $html;
    }

    /**
     * @inheritDoc
     */
    public function view(): void
    {
        extract($this->variables);

        ob_start();
        if (!empty($this->path)) {
            include $this->path;
        }
        $content = ob_get_clean();

        $this->sendHeaders();
        $this->sendStatusCode();
        echo $content;
    }

    /**
     * @inheritDoc
     */
    public static function createNotFound(string $message = "Page not found."): self
    {
        return new self("<h1>404 Not Found</h1><p>" . htmlspecialchars($message) . "</p>", statusCode: 404);
    }

    /**
     * @inheritDoc
     */
    public static function createBadRequest(string $message = "Bad Request."): self
    {
        return new self("<h1>400 Bad Request</h1><p>" . htmlspecialchars($message) . "</p>", statusCode: 400);
    }

    /**
     * @inheritDoc
     */
    public static function createInternalServerError(string $message = "Bad Request."): self
    {
        return new self("<h1>500 Internal Server Error</h1><p>"
            . htmlspecialchars($message)
            . "</p>", statusCode: 500);
    }
}
<?php
namespace App\Utils;

class HtmlResponse
{
    private int $statusCode;
    private string $htmlContent;

    public function __construct(int $statusCode, string $htmlContent)
    {
        $this->statusCode = $statusCode;
        $this->htmlContent = $htmlContent;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        header('Content-Type: text/html; charset=UTF-8');
        echo $this->htmlContent;
    }
} 
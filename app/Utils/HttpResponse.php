<?php
namespace App\Utils;

class HttpResponse
{
    private int $statusCode;
    private array $data;

    public function __construct(int $statusCode, array $data)
    {
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');
        echo json_encode($this->data);
    }
}
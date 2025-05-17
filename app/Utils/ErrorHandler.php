// app/Utils/ErrorHandler.php
namespace App\Utils;

use App\Services\LoggerService;

class ErrorHandler {
    public static function handle(\Throwable $e, LoggerService $logger): HttpResponse {
        $logger->log($e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 'error');

        return new HttpResponse(500, [
            'error' => 'Erro interno do servidor',
            'code' => $e->getCode()
        ]);
    }
}
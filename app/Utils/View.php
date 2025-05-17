<?php
namespace App\Utils;

class View {
    private static $basePath = __DIR__ . '/../../resources/views/';

    public static function render(string $template, array $data = []): string {
        extract($data);
        
        // Captura o conteúdo do template
        ob_start();
        include self::$basePath . $template . '.php';
        $content = ob_get_clean();

        // Se não for uma partial, inclui o layout
        if (!str_contains($template, 'components/')) {
            return self::renderWithLayout($content, $data);
        }
        
        return $content;
    }

    private static function renderWithLayout(string $content, array $data): string {
        extract($data);
        ob_start();
        include self::$basePath . 'layouts/base.php';
        return ob_get_clean();
    }

    // Helper para assets
    public static function asset(string $path): string {
        return '/assets/' . ltrim($path, '/');
    }
}
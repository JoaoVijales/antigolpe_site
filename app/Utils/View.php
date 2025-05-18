<?php
namespace App\Utils;

class View {
    private static $basePath = __DIR__ . '/../../resources/views/';
    private static $publicPath = __DIR__ . '/../../public/'; // Adicionado caminho para o diretório public
    private static $translations = []; // Placeholder para traduções

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

    // Helper para assets (URLs)
    public static function asset(string $path): string {
        return '/assets/' . ltrim($path, '/ '); // Adicionado ltrim para garantir barra inicial única
    }

    // Placeholder para helper de tradução
    public static function translate(string $key): string {
        // Implementação básica: retorna a chave ou uma tradução se existir
        return self::$translations[$key] ?? $key; // Retorna a chave por enquanto
    }

    // Helper para escapar saída HTML
    public static function escape(string $value): string {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    // Helper para carregar conteúdo de arquivos SVG
    public static function svg(string $iconName): string {
        $svgFilePath = self::$publicPath . 'assets/icons/' . $iconName . '.svg';

        if (file_exists($svgFilePath)) {
            return file_get_contents($svgFilePath);
        } else {
            // Retorna um placeholder ou loga um erro se o arquivo SVG não for encontrado
            error_log("SVG file not found: " . $svgFilePath);
            return "<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><circle cx=\"12\" cy=\"12\" r=\"10\" fill=\"red\"/></svg>"; // Placeholder simples de erro
        }
    }

    public static function image(string $path): string {
        $imagePath = self::$publicPath . 'assets/images/' . $path;
        if (file_exists($imagePath)) {
            return $imagePath;
        } else {
            error_log("Image file not found: " . $imagePath);
            return self::$publicPath . 'assets/images/placeholder.png';
        }
    }
}
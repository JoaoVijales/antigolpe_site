<?php
namespace App\Security;

/**
 * Classe FileUploadSecurity
 * 
 * Implementa medidas de segurança para upload de arquivos, incluindo:
 * - Validação de tipos MIME
 * - Verificação de tamanho
 * - Análise de conteúdo
 * - Proteção contra arquivos maliciosos
 * 
 * @package Security
 * @author Sistema de Segurança
 * @version 1.0.0
 */
class FileUploadSecurity {
    /** @var array Tipos MIME permitidos para upload */
    private $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    /** @var int Tamanho máximo do arquivo em bytes (5MB) */
    private $maxFileSize = 5242880;

    /** @var string Diretório de upload */
    private $uploadDir;

    /** @var array Lista de erros encontrados durante a validação */
    private $errors = [];

    /**
     * Construtor da classe
     * 
     * @param string $uploadDir Diretório onde os arquivos serão salvos
     */
    public function __construct($uploadDir = 'uploads/') {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
        
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Valida um arquivo enviado
     * 
     * Realiza várias verificações de segurança:
     * - Verifica se o arquivo foi realmente enviado via upload
     * - Verifica o tamanho do arquivo
     * - Verifica o tipo MIME
     * - Verifica a extensão
     * - Analisa o conteúdo em busca de código malicioso
     * 
     * @param array $file Array $_FILES do arquivo enviado
     * @return bool true se o arquivo for válido, false caso contrário
     */
    public function validateFile($file) {
        $this->errors = [];

        // Verificar se o arquivo foi enviado
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $this->errors[] = "Nenhum arquivo foi enviado";
            return false;
        }

        // Verificar tamanho do arquivo
        if ($file['size'] > $this->maxFileSize) {
            $this->errors[] = "O arquivo excede o tamanho máximo permitido";
            return false;
        }

        // Verificar tipo do arquivo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $this->allowedTypes)) {
            $this->errors[] = "Tipo de arquivo não permitido";
            return false;
        }

        // Verificar extensão do arquivo
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
        
        if (!in_array($extension, $allowedExtensions)) {
            $this->errors[] = "Extensão de arquivo não permitida";
            return false;
        }

        // Verificar conteúdo do arquivo
        if (!$this->isFileContentSafe($file['tmp_name'], $mimeType)) {
            $this->errors[] = "O conteúdo do arquivo parece ser malicioso";
            return false;
        }

        return true;
    }

    /**
     * Verifica se o conteúdo do arquivo é seguro
     * 
     * Realiza verificações de segurança no conteúdo do arquivo:
     * - Verifica a assinatura do arquivo (magic numbers)
     * - Procura por código PHP malicioso
     * - Procura por comandos shell
     * 
     * @param string $filePath Caminho do arquivo
     * @param string $mimeType Tipo MIME do arquivo
     * @return bool true se o conteúdo for seguro, false caso contrário
     */
    private function isFileContentSafe($filePath, $mimeType) {
        // Verificar assinatura do arquivo
        $signatures = [
            'image/jpeg' => "\xFF\xD8\xFF",
            'image/png' => "\x89PNG\r\n\x1a\n",
            'image/gif' => "GIF87a",
            'application/pdf' => "%PDF-",
        ];

        if (isset($signatures[$mimeType])) {
            $handle = fopen($filePath, 'rb');
            $header = fread($handle, 8);
            fclose($handle);

            if (strpos($header, $signatures[$mimeType]) !== 0) {
                return false;
            }
        }

        // Verificar por conteúdo malicioso
        $content = file_get_contents($filePath);
        
        // Verificar por scripts PHP
        if (strpos($content, '<?php') !== false || strpos($content, '<?=') !== false) {
            return false;
        }

        // Verificar por comandos shell
        $shellCommands = ['system', 'exec', 'shell_exec', 'passthru', 'eval', 'assert'];
        foreach ($shellCommands as $cmd) {
            if (stripos($content, $cmd) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Salva um arquivo validado no diretório de upload
     * 
     * @param array $file Array $_FILES do arquivo enviado
     * @return string|bool Nome do arquivo salvo ou false em caso de erro
     */
    public function saveFile($file) {
        if (!$this->validateFile($file)) {
            return false;
        }

        // Gerar nome único para o arquivo
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newFileName = uniqid() . '_' . time() . '.' . $extension;
        $targetPath = $this->uploadDir . $newFileName;

        // Mover arquivo para o diretório de upload
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            $this->errors[] = "Erro ao salvar o arquivo";
            return false;
        }

        // Definir permissões corretas
        chmod($targetPath, 0644);

        return $newFileName;
    }

    /**
     * Retorna a lista de erros encontrados durante a validação
     * 
     * @return array Lista de mensagens de erro
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Define os tipos MIME permitidos
     * 
     * @param array $types Lista de tipos MIME permitidos
     */
    public function setAllowedTypes($types) {
        $this->allowedTypes = $types;
    }

    /**
     * Define o tamanho máximo permitido para upload
     * 
     * @param int $size Tamanho máximo em bytes
     */
    public function setMaxFileSize($size) {
        $this->maxFileSize = $size;
    }

    /**
     * Define o diretório de upload
     * 
     * @param string $dir Caminho do diretório
     */
    public function setUploadDir($dir) {
        $this->uploadDir = rtrim($dir, '/') . '/';
        
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
}
?> 
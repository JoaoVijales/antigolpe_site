<?php
namespace App\Utils;

use PDO;
use PDOException;
use Exception;

class Database {
    private static $instance = null;
    private $connection;
    private $preparedStatements = [];

    public function __construct() {
        try {
            $host = getenv('DB_HOST') ?? 'localhost';
            $dbname = getenv('DB_NAME') ?? 'u906925595_security';
            $username = getenv('DB_USER') ?? 'u906925595_security';
            $password = getenv('DB_PASS') ?? '';

            $this->connection = new PDO(
                "mysql:host={$host};dbname={$dbname}",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                ]
            );
        } catch (PDOException $e) {
            error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
            throw new Exception("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function prepare($query) {
        try {
            $hash = md5($query);
            if (!isset($this->preparedStatements[$hash])) {
                $this->preparedStatements[$hash] = $this->connection->prepare($query);
            }
            return $this->preparedStatements[$hash];
        } catch (PDOException $e) {
            error_log("Erro ao preparar query: " . $e->getMessage() . "\nQuery: " . $query);
            throw new Exception("Erro ao preparar query: " . $e->getMessage());
        }
    }

    public function query($query, $params = []) {
        try {
            $stmt = $this->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Erro na query: " . $e->getMessage() . "\nQuery: " . $query . "\nParâmetros: " . json_encode($params));
            throw new Exception("Erro ao executar a query: " . $e->getMessage());
        }
    }

    public function insert($table, $data) {
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        $query = "INSERT INTO {$table} (" . implode(', ', $fields) . ") 
                 VALUES (" . implode(', ', $placeholders) . ")";
        
        $this->query($query, array_values($data));
        return $this->connection->lastInsertId();
    }

    public function update($table, $data, $where, $whereParams = []) {
        $fields = array_map(function($field) {
            return "{$field} = ?";
        }, array_keys($data));
        
        $query = "UPDATE {$table} SET " . implode(', ', $fields) . " WHERE {$where}";
        
        $params = array_merge(array_values($data), $whereParams);
        return $this->query($query, $params);
    }

    public function delete($table, $where, $params = []) {
        $query = "DELETE FROM {$table} WHERE {$where}";
        return $this->query($query, $params);
    }

    public function select($table, $fields = '*', $where = '1', $params = [], $orderBy = null, $limit = null) {
        $query = "SELECT {$fields} FROM {$table} WHERE {$where}";
        
        if ($orderBy) {
            $query .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $query .= " LIMIT {$limit}";
        }
        
        return $this->query($query, $params)->fetchAll();
    }

    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }

    public function commit() {
        return $this->connection->commit();
    }

    public function rollback() {
        return $this->connection->rollBack();
    }

    public function quote($value) {
        return $this->connection->quote($value);
    }

    public function getLastError() {
        return $this->connection->errorInfo();
    }

    public function __clone() {}
    public function __wakeup() {}
}
?> 
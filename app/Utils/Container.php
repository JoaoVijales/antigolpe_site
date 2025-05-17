<?php
namespace App\Utils;

class Container {
    private static $instance;
    private $services = [];
    private $config;

    private function __construct() {
        $this->config = require __DIR__ . '/../app/config/services.php';
    }

    public static function getInstance(): self {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get(string $serviceName) {
        if (!isset($this->services[$serviceName])) {
            $this->services[$serviceName] = $this->config[$serviceName]($this);
        }
        return $this->services[$serviceName];
    }

    public function resolve(string $className) {
        $reflection = new \ReflectionClass($className);
        $constructor = $reflection->getConstructor();
        
        if (!$constructor) {
            return new $className();
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            if ($type && !$type->isBuiltin()) {
                $dependencies[] = $this->get($type->getName());
            } else {
                throw new \RuntimeException("Não é possível resolver a dependência: {$parameter->getName()}");
            }
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}

// Inicializa o container quando a classe é carregada
Container::initialize();
<?php
// app/Services/ValidationService.php
namespace App\Services;

class ValidationService {
    private array $errors = [];
    
    public function validate(array $data, array $rules): bool {
        foreach ($rules as $field => $validations) {
            foreach ($validations as $validation) {
                $this->applyRule($field, $data[$field] ?? null, $validation);
            }
        }
        return empty($this->errors);
    }

    private function applyRule(string $field, $value, string $rule): void {
        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $this->errors[$field][] = "O campo $field é obrigatório";
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "Formato de email inválido";
                }
                break;
            case 'password':
                if (strlen($value) < 8 || !preg_match('/[A-Z]/', $value) || !preg_match('/[0-9]/', $value)) {
                    $this->errors[$field][] = "Senha deve ter pelo menos 8 caracteres, 1 letra maiúscula e 1 número";
                }
                break;
        }
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function getFirstError(): ?string {
        return $this->errors ? reset($this->errors)[0] : null;
    }
}
<?php
// app/Api/Firebase/CreateUser.php

// Incluir o autoload do Composer para carregar as dependências (incluindo Dotenv e Firebase Admin SDK)
require_once __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;

header('Content-Type: application/json'); // Garantir que a resposta é JSON

// Carregar variáveis de ambiente
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

// Verificar se a chave secreta do Firebase Admin SDK está definida
$firebaseServiceAccountPath = $_ENV['FIREBASE_ADMIN_SDK_SECRET_KEY'] ?? null;
$firebaseProjectId = $_ENV['FIREBASE_PROJECT_ID'] ?? null; // Pode ser necessário dependendo da inicialização

// Validar se a chave secreta foi carregada
if (!$firebaseServiceAccountPath) {
    http_response_code(500);
    echo json_encode(['error' => 'Firebase Admin SDK secret key not configured in environment.']);
    exit;
}

// **Importante:** A linha abaixo assume que FIREBASE_ADMIN_SDK_SECRET_KEY contém o *caminho* para o arquivo JSON da chave de serviço.
// Se FIREBASE_ADMIN_SDK_SECRET_KEY contém o *conteúdo* do JSON, a inicialização seria diferente.
// Consulte a documentação do Kreait Firebase Admin SDK para a inicialização correta com base no seu método de armazenamento da chave.
try {
    $firebase = (new Factory)
        ->withServiceAccount($firebaseServiceAccountPath)
        ->withProjectId($firebaseProjectId); // Use withProjectId se necessário

    $auth = $firebase->getAuth();

} catch (\InvalidArgumentException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error initializing Firebase Admin SDK: ' . $e->getMessage()]);
    exit;
} catch (FirebaseException $e) {
     http_response_code(500);
    echo json_encode(['error' => 'Firebase SDK Exception during initialization: ' . $e->getMessage()]);
    exit;
}


// Lógica para lidar com a requisição de criação de usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter os dados do corpo da requisição POST (assumindo JSON)
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;
    // Adicionar validação e sanitização de input aqui!

    if ($email && $password) {
        try {
            // Criar o usuário usando o Firebase Admin SDK (backend seguro)
            $userProperties = [
                'email' => $email,
                'password' => $password,
                'emailVerified' => false,
                'disabled' => false,
            ];
            $userRecord = $auth->createUser($userProperties);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Usuário criado com sucesso.',
                'uid' => $userRecord->uid,
                'email' => $userRecord->email
                ]);

        } catch (\Kreait\Firebase\Exception\Auth\EmailAlreadyExists $e) {
            http_response_code(409); // Conflict
            echo json_encode(['error' => 'Email já existe.']);
        } catch (\Kreait\Firebase\Exception\Auth\WeakPassword $e) {
             http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Senha muito fraca.']);
        }
         catch (\Throwable $e) {
            // Capturar outras exceções do Firebase ou erros gerais
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao criar usuário: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Email e senha são obrigatórios.']);
    }
} else {
    // Método HTTP não permitido
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Método não permitido.']);
} 
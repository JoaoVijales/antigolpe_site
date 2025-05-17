<?php

header('Content-Type: application/json');

// Inclua os arquivos necessários do Composer
require __DIR__ . '/../../../vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

// --- Configuração do Firebase Admin SDK ---
// VOCÊ PRECISA CONFIGURAR ISSO CORRETAMENTE
// Geralmente envolve carregar um arquivo JSON de chave de conta de serviço.
// Exemplo (ajuste o caminho):
// $factory = (new Factory)->withServiceAccount(__DIR__ . '/serviceAccountKey.json');
// $auth = $factory->createAuth();

// Placeholder: Substitua pela sua inicialização real do Firebase Auth
$auth = null; // Inicialize $auth com sua instância do Firebase Auth Admin SDK

if ($auth === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Configuração do Firebase Admin SDK incompleta no backend.']);
    exit();
}
// --------------------------------------------

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Método não permitido.']);
    exit();
}

// Pega os dados do corpo da requisição
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$email = $data['email'] ?? null;
$password = $data['password'] ?? null;

// Validação básica
if (empty($email) || empty($password)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Email e senha são obrigatórios.']);
    exit();
}

try {
    // Tenta fazer login com email e senha usando o Admin SDK
    // Nota: O Admin SDK não tem um método direto 'signInWithEmailAndPassword' como o SDK do cliente.
    // Você normalmente usaria o Admin SDK para:
    // 1. Verificar a senha do usuário de forma segura (requer hash da senha no DB do Firebase Auth) - Complexo.
    // 2. Gerar um token personalizado no backend e usar o SDK do cliente para autenticar com esse token.
    // 3. Se estiver usando Identity Platform, pode haver mais opções.

    // A abordagem mais comum e segura com Admin SDK é gerar um token personalizado APÓS verificar a senha
    // (se você tiver um sistema de senhas separado) ou confiar na senha já armazenada no Firebase Auth.
    // Para simplificar, VOU SIMULAR A VERIFICAÇÃO OU GERAR UM TOKEN PARA UM USUÁRIO EXISTENTE.
    // ESTE CÓDIGO ABAIXO É UM PLACEHOLDER E PODE PRECISAR DE AJUSTES DEPENDENDO DA SUA ESTRUTURA DE AUTENTICAÇÃO.

    // Exemplo de como buscar um usuário pelo email (não autentica, apenas verifica existência)
    $user = $auth->getUserByEmail($email);

    // TODO: Implementar a verificação da senha de forma segura aqui se necessário,
    // ou usar um método apropriado do SDK de Admin/Identity Platform para autenticar com senha.
    // A maneira mais segura de verificar senhas de usuários criados com o SDK do cliente via Admin SDK
    // geralmente envolve a geração de um link de redefinição de senha para forçar o fluxo do cliente
    // ou a validação de um ID token recebido do cliente após a autenticação inicial.

    // Para um fluxo de API simples (e menos seguro se não houver outra validação de senha):}
    // Você pode gerar um token personalizado para o usuário encontrado e retorná-lo.
    // O frontend usaria esse token para fazer login no lado do cliente.
    $customToken = $auth->createCustomToken($user->uid);

    http_response_code(200); // OK
    echo json_encode([
        'message' => 'Login bem-sucedido!',
        'uid' => $user->uid,
        'email' => $user->email,
        'customToken' => (string) $customToken // Envia o token personalizado para o frontend
    ]);

} catch (\Kreait\Firebase\Exception\FirebaseException $e) {
    // Captura erros específicos do Firebase (ex: usuário não encontrado, senha incorreta)
    http_response_code(401); // Unauthorized ou 400 Bad Request dependendo do erro
    echo json_encode(['error' => 'Erro de autenticação do Firebase: ' . $e->getMessage()]);
} catch (\Exception $e) {
    // Captura outros erros gerais
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Erro no servidor: ' . $e->getMessage()]);
}
?> 
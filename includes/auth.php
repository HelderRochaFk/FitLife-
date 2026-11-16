<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function exigirLogin(): void {
    if (!isset($_SESSION['usuario'])) {
        header('Location: /fitlife/login.php');
        exit;
    }
}

function exigirPerfil(string $perfil): void {
    exigirLogin();
    if ($_SESSION['usuario']['perfil'] !== $perfil) {
        http_response_code(403);
        die('Acesso não autorizado.');
    }
}

function usuarioAtual(): array {
    return $_SESSION['usuario'] ?? [];
}

function tokenCsrf(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function validarCsrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(419);
        exit('Sua sessão do formulário expirou. Volte, atualize a página e tente novamente.');
    }
}

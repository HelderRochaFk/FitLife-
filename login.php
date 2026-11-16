<?php
require __DIR__ . '/config/conexao.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['usuario'])) { header('Location: /fitlife/' . $_SESSION['usuario']['perfil'] . '/dashboard.php'); exit; }
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $stmt = $pdo->prepare("SELECT id,nome,email,senha,perfil FROM usuarios WHERE email=? AND ativo=1");
    $stmt->execute([$email]);
    $u = $stmt->fetch();
    if ($u && password_verify($senha, $u['senha'])) {
        unset($u['senha']);
        $_SESSION['usuario'] = $u;
        header('Location: /fitlife/' . $u['perfil'] . '/dashboard.php');
        exit;
    }
    $erro = 'E-mail ou senha inválidos.';
}
$titulo='Entrar';
include __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center py-5">
 <div class="col-md-6 col-lg-5">
  <div class="fit-card p-4 p-md-5">
   <span class="text-fit fw-bold">BEM-VINDO</span><h1 class="mb-4">Entrar</h1>
   <?php if($erro): ?><div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
   <form method="post">
    <label class="form-label">E-mail</label><input class="form-control mb-3" type="email" name="email" required>
    <label class="form-label">Senha</label><input class="form-control mb-4" type="password" name="senha" required>
    <button class="btn btn-fit w-100 py-2">Entrar</button>
   </form>
   <p class="muted mt-4 mb-0">Ainda não tem conta? <a class="text-fit" href="/fitlife/cadastro.php">Cadastre-se</a></p>
  </div>
 </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>

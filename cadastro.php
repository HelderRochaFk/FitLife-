<?php
require __DIR__ . '/config/conexao.php';
$erro='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $nome=trim($_POST['nome']??''); $email=trim($_POST['email']??''); $cpf=trim($_POST['cpf']??'');
    $telefone=trim($_POST['telefone']??''); $nascimento=$_POST['nascimento']??null; $senha=$_POST['senha']??'';
    if (!$nome || !$email || !$senha) $erro='Preencha os campos obrigatórios.';
    elseif (strlen($senha)<6) $erro='A senha deve ter pelo menos 6 caracteres.';
    else {
        try {
            $pdo->beginTransaction();
            $stmt=$pdo->prepare("INSERT INTO usuarios(nome,email,senha,perfil) VALUES(?,?,?,'aluno')");
            $stmt->execute([$nome,$email,password_hash($senha,PASSWORD_DEFAULT)]);
            $uid=(int)$pdo->lastInsertId();
            $stmt=$pdo->prepare("INSERT INTO alunos(usuario_id,cpf,telefone,data_nascimento) VALUES(?,?,?,?)");
            $stmt->execute([$uid,$cpf,$telefone,$nascimento ?: null]);
            $pdo->commit();
            header('Location: /fitlife/login.php?cadastro=ok'); exit;
        } catch(PDOException $e) { $pdo->rollBack(); $erro=$e->getCode()==='23000'?'E-mail ou CPF já cadastrado.':'Não foi possível concluir o cadastro.'; }
    }
}
$titulo='Cadastro';
include __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center py-3">
 <div class="col-lg-8">
  <div class="fit-card p-4 p-md-5">
   <span class="text-fit fw-bold">FITLIFE</span><h1>Crie sua conta</h1><p class="muted">O cadastro inicial é para alunos.</p>
   <?php if($erro): ?><div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
   <form method="post" class="row g-3">
    <div class="col-md-6"><label class="form-label">Nome completo *</label><input class="form-control" name="nome" required></div>
    <div class="col-md-6"><label class="form-label">E-mail *</label><input class="form-control" type="email" name="email" required></div>
    <div class="col-md-6"><label class="form-label">CPF</label><input class="form-control" name="cpf"></div>
    <div class="col-md-6"><label class="form-label">Telefone</label><input class="form-control" name="telefone"></div>
    <div class="col-md-6"><label class="form-label">Data de nascimento</label><input class="form-control" type="date" name="nascimento"></div>
    <div class="col-md-6"><label class="form-label">Senha *</label><input class="form-control" type="password" name="senha" minlength="6" required></div>
    <div class="col-12"><button class="btn btn-fit px-4">Criar conta</button></div>
   </form>
  </div>
 </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>

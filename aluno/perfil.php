<?php
require __DIR__.'/../config/conexao.php'; require __DIR__.'/../includes/auth.php'; exigirPerfil('aluno');
$uid=$_SESSION['usuario']['id']; $msg='';
if($_SERVER['REQUEST_METHOD']==='POST') {
 $nome=trim($_POST['nome']);$email=trim($_POST['email']);$telefone=trim($_POST['telefone']);
 $pdo->prepare("UPDATE usuarios SET nome=?,email=? WHERE id=?")->execute([$nome,$email,$uid]);
 $pdo->prepare("UPDATE alunos SET telefone=? WHERE usuario_id=?")->execute([$telefone,$uid]);
 $_SESSION['usuario']['nome']=$nome; $_SESSION['usuario']['email']=$email; $msg='Perfil atualizado.';
}
$stmt=$pdo->prepare("SELECT u.nome,u.email,a.telefone,a.cpf,a.data_nascimento FROM usuarios u JOIN alunos a ON a.usuario_id=u.id WHERE u.id=?");$stmt->execute([$uid]);$u=$stmt->fetch();
$titulo='Meu perfil';include __DIR__.'/../includes/header.php';
?>
<div class="col-lg-7"><span class="text-fit fw-bold">CONTA</span><h1 class="dashboard-title">Meu perfil</h1><?php if($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?><div class="fit-card p-4 mt-3"><form method="post"><label class="form-label">Nome</label><input class="form-control mb-3" name="nome" value="<?= htmlspecialchars($u['nome']) ?>" required><label class="form-label">E-mail</label><input class="form-control mb-3" type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>" required><label class="form-label">Telefone</label><input class="form-control mb-3" name="telefone" value="<?= htmlspecialchars($u['telefone']??'') ?>"><label class="form-label">CPF</label><input class="form-control mb-4" value="<?= htmlspecialchars($u['cpf']??'') ?>" disabled><button class="btn btn-fit">Salvar alterações</button></form></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>

<?php
require __DIR__.'/../config/conexao.php';require __DIR__.'/../includes/auth.php';exigirPerfil('admin');
if(isset($_GET['excluir'])){$id=(int)$_GET['excluir'];$pdo->prepare("DELETE FROM usuarios WHERE id=(SELECT usuario_id FROM alunos WHERE id=?)")->execute([$id]);}
$alunos=$pdo->query("SELECT a.id,u.id usuario_id,u.nome,u.email,a.cpf,a.telefone FROM alunos a JOIN usuarios u ON u.id=a.usuario_id ORDER BY u.nome")->fetchAll();$titulo='Alunos';include __DIR__.'/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4"><h1 class="dashboard-title">Alunos</h1><a class="btn btn-fit" href="/fitlife/cadastro.php">Novo aluno</a></div>
<div class="table-responsive fit-card p-3"><table class="table align-middle"><thead><tr><th>Nome</th><th>E-mail</th><th>CPF</th><th>Telefone</th><th></th></tr></thead><tbody><?php foreach($alunos as $a): ?><tr><td><?=htmlspecialchars($a['nome'])?></td><td><?=htmlspecialchars($a['email'])?></td><td><?=htmlspecialchars($a['cpf']??'-')?></td><td><?=htmlspecialchars($a['telefone']??'-')?></td><td><a data-confirm="Excluir este aluno e sua conta?" class="btn btn-sm btn-outline-danger" href="?excluir=<?=$a['id']?>">Excluir</a></td></tr><?php endforeach;?></tbody></table></div>
<?php include __DIR__.'/../includes/footer.php'; ?>

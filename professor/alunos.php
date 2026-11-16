<?php
require __DIR__.'/../config/conexao.php';require __DIR__.'/../includes/auth.php';exigirPerfil('professor');
$stmt=$pdo->prepare("SELECT DISTINCT u.nome,u.email,a.telefone FROM agendamentos ag JOIN aulas au ON au.id=ag.aula_id JOIN alunos a ON a.id=ag.aluno_id JOIN usuarios u ON u.id=a.usuario_id WHERE au.professor_id=(SELECT id FROM professores WHERE usuario_id=?) AND ag.status='confirmado' ORDER BY u.nome");
$stmt->execute([$_SESSION['usuario']['id']]);$alunos=$stmt->fetchAll();$titulo='Meus alunos';include __DIR__.'/../includes/header.php';
?>
<div class="mb-4"><span class="text-fit fw-bold">ALUNOS</span><h1 class="dashboard-title">Meus alunos</h1></div>
<div class="table-responsive fit-card p-3"><table class="table"><thead><tr><th>Nome</th><th>E-mail</th><th>Telefone</th></tr></thead><tbody><?php foreach($alunos as $a): ?><tr><td><?=htmlspecialchars($a['nome'])?></td><td><?=htmlspecialchars($a['email'])?></td><td><?=htmlspecialchars($a['telefone']??'-')?></td></tr><?php endforeach; if(!$alunos): ?><tr><td colspan="3" class="muted">Nenhum aluno encontrado.</td></tr><?php endif;?></tbody></table></div>
<?php include __DIR__.'/../includes/footer.php'; ?>

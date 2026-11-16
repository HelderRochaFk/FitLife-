<?php
require __DIR__.'/../config/conexao.php'; require __DIR__.'/../includes/auth.php'; exigirPerfil('aluno');
$uid=$_SESSION['usuario']['id'];
$stmt=$pdo->prepare("SELECT a.id,u.nome,u.email,a.cpf,a.telefone,a.data_nascimento FROM alunos a JOIN usuarios u ON u.id=a.usuario_id WHERE u.id=?"); $stmt->execute([$uid]); $aluno=$stmt->fetch();
$stmt=$pdo->prepare("SELECT ag.*,a.nome aula,a.horario,u.nome AS professor FROM agendamentos ag JOIN aulas a ON a.id=ag.aula_id JOIN professores p ON p.id=a.professor_id JOIN usuarios u ON u.id=p.usuario_id WHERE ag.aluno_id=? AND CONCAT(ag.data,' ',a.horario)>=NOW() AND ag.status='confirmado' ORDER BY ag.data,a.horario LIMIT 1");
$stmt->execute([$aluno['id']]); $proxima=$stmt->fetch();
$stmt=$pdo->prepare("SELECT COUNT(*) FROM agendamentos WHERE aluno_id=? AND status='confirmado'"); $stmt->execute([$aluno['id']]); $total=$stmt->fetchColumn();
$titulo='Painel do aluno'; include __DIR__.'/../includes/header.php';
?>
<div class="mb-4"><span class="text-fit fw-bold">ÁREA DO ALUNO</span><h1 class="dashboard-title">Olá, <?= htmlspecialchars($aluno['nome']) ?> 👋</h1></div>
<div class="row g-3 mb-4">
 <div class="col-md-4"><div class="stat-card"><div class="muted">Aulas agendadas</div><div class="stat-number"><?= $total ?></div></div></div>
 <div class="col-md-8"><div class="stat-card h-100"><div class="muted">Próxima aula</div><strong><?= $proxima ? htmlspecialchars($proxima['aula']) : 'Nenhuma aula agendada' ?></strong><?php if($proxima): ?><div class="muted"><?= date('d/m/Y',strtotime($proxima['data'])) ?> às <?= date('H:i',strtotime($proxima['horario'])) ?> • <?= htmlspecialchars($proxima['professor']) ?></div><?php endif; ?></div></div>
</div>
<div class="d-flex flex-wrap gap-2"><a class="btn btn-fit" href="/fitlife/aluno/aulas.php">Agendar aula</a><a class="btn btn-outline-light" href="/fitlife/aluno/treinos.php">Meus treinos</a><a class="btn btn-outline-light" href="/fitlife/aluno/perfil.php">Meu perfil</a></div>
<?php include __DIR__.'/../includes/footer.php'; ?>

<?php
require __DIR__.'/../config/conexao.php'; require __DIR__.'/../includes/auth.php'; exigirPerfil('professor');
$stmt=$pdo->prepare("SELECT id FROM professores WHERE usuario_id=?");$stmt->execute([$_SESSION['usuario']['id']]);$pid=$stmt->fetchColumn();
$stmt=$pdo->prepare("SELECT COUNT(*) FROM aulas WHERE professor_id=?");$stmt->execute([$pid]);$aulas=$stmt->fetchColumn();
$stmt=$pdo->prepare("SELECT COUNT(DISTINCT aluno_id) FROM agendamentos ag JOIN aulas a ON a.id=ag.aula_id WHERE a.professor_id=? AND ag.status='confirmado'");$stmt->execute([$pid]);$alunos=$stmt->fetchColumn();
$titulo='Painel do professor';include __DIR__.'/../includes/header.php';
?>
<div class="mb-4"><span class="text-fit fw-bold">ÁREA DO PROFESSOR</span><h1 class="dashboard-title">Olá, <?= htmlspecialchars($_SESSION['usuario']['nome']) ?> 👋</h1></div>
<div class="row g-3 mb-4"><div class="col-md-6"><div class="stat-card"><div class="muted">Minhas aulas</div><div class="stat-number"><?= $aulas ?></div></div></div><div class="col-md-6"><div class="stat-card"><div class="muted">Alunos atendidos</div><div class="stat-number"><?= $alunos ?></div></div></div></div>
<div class="d-flex gap-2 flex-wrap"><a class="btn btn-fit" href="/fitlife/professor/aulas.php">Minhas aulas</a><a class="btn btn-outline-light" href="/fitlife/professor/alunos.php">Meus alunos</a><a class="btn btn-outline-light" href="/fitlife/professor/treinos.php">Treinos</a></div>
<?php include __DIR__.'/../includes/footer.php'; ?>

<?php
require __DIR__.'/../config/conexao.php'; require __DIR__.'/../includes/auth.php'; exigirPerfil('aluno');
$stmt=$pdo->prepare("SELECT al.id FROM alunos al WHERE al.usuario_id=?"); $stmt->execute([$_SESSION['usuario']['id']]); $id=$stmt->fetchColumn();
$stmt=$pdo->prepare("SELECT t.*,u.nome AS professor FROM treinos t LEFT JOIN professores p ON p.id=t.professor_id LEFT JOIN usuarios u ON u.id=p.usuario_id WHERE t.aluno_id=? ORDER BY t.criado_em DESC"); $stmt->execute([$id]); $treinos=$stmt->fetchAll();
$titulo='Meus treinos'; include __DIR__.'/../includes/header.php';
?>
<div class="mb-4"><span class="text-fit fw-bold">PLANO</span><h1 class="dashboard-title">Meus treinos</h1></div>
<div class="row g-4"><?php foreach($treinos as $t): ?><div class="col-md-6"><div class="fit-card p-4"><h3><?= htmlspecialchars($t['titulo']) ?></h3><div class="muted mb-3">Professor: <?= htmlspecialchars($t['professor'] ?? 'Equipe Fitlife') ?></div><p><?= nl2br(htmlspecialchars($t['descricao'])) ?></p></div></div><?php endforeach; if(!$treinos): ?><div class="col-12"><div class="fit-card p-4 muted">Ainda não há treinos cadastrados para você.</div></div><?php endif; ?></div>
<?php include __DIR__.'/../includes/footer.php'; ?>

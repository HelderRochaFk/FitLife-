<?php
require __DIR__ . '/config/conexao.php';
$titulo = 'Aulas';
$stmt = $pdo->query("SELECT a.*, u.nome AS professor FROM aulas a JOIN professores p ON p.id=a.professor_id JOIN usuarios u ON u.id=p.usuario_id ORDER BY a.dia_semana, a.horario");
$aulas = $stmt->fetchAll();
$dia = [1=>'Segunda',2=>'Terça',3=>'Quarta',4=>'Quinta',5=>'Sexta',6=>'Sábado',7=>'Domingo'];
include __DIR__ . '/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
 <div><span class="text-fit fw-bold">AGENDA</span><h1 class="dashboard-title">Nossas aulas</h1></div>
</div>
<div class="row g-4">
<?php foreach ($aulas as $a): ?>
 <div class="col-md-6 col-lg-4">
  <div class="fit-card p-4 h-100">
   <span class="badge badge-fit mb-3"><?= htmlspecialchars($dia[$a['dia_semana']]) ?></span>
   <h3><?= htmlspecialchars($a['nome']) ?></h3>
   <p class="muted"><?= htmlspecialchars($a['descricao']) ?></p>
   <div class="small muted mb-1">Professor</div><div><?= htmlspecialchars($a['professor']) ?></div>
   <div class="d-flex justify-content-between mt-3 pt-3 border-top border-secondary">
    <span><?= date('H:i', strtotime($a['horario'])) ?> • <?= (int)$a['duracao_min'] ?> min</span>
    <span><?= (int)$a['vagas'] ?> vagas</span>
   </div>
  </div>
 </div>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>

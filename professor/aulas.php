<?php
require __DIR__.'/../config/conexao.php';require __DIR__.'/../includes/auth.php';exigirPerfil('professor');
$stmt=$pdo->prepare("SELECT id FROM professores WHERE usuario_id=?");$stmt->execute([$_SESSION['usuario']['id']]);$pid=$stmt->fetchColumn();
$stmt=$pdo->prepare("SELECT * FROM aulas WHERE professor_id=? ORDER BY dia_semana,horario");$stmt->execute([$pid]);$aulas=$stmt->fetchAll();
$dia=[1=>'Segunda',2=>'Terça',3=>'Quarta',4=>'Quinta',5=>'Sexta',6=>'Sábado',7=>'Domingo'];$titulo='Minhas aulas';include __DIR__.'/../includes/header.php';
?>
<div class="mb-4"><span class="text-fit fw-bold">AGENDA</span><h1 class="dashboard-title">Minhas aulas</h1></div>
<div class="table-responsive fit-card p-3"><table class="table"><thead><tr><th>Aula</th><th>Dia</th><th>Horário</th><th>Vagas</th></tr></thead><tbody><?php foreach($aulas as $a): ?><tr><td><?=htmlspecialchars($a['nome'])?></td><td><?=$dia[$a['dia_semana']]?></td><td><?=date('H:i',strtotime($a['horario']))?></td><td><?=$a['vagas']?></td></tr><?php endforeach;?></tbody></table></div>
<?php include __DIR__.'/../includes/footer.php'; ?>

<?php
require __DIR__.'/../config/conexao.php';require __DIR__.'/../includes/auth.php';exigirPerfil('admin');
$ag=$pdo->query("SELECT ag.id,ag.data,ag.status,a.nome aula,up.nome professor,u.nome aluno FROM agendamentos ag JOIN aulas a ON a.id=ag.aula_id JOIN professores p ON p.id=a.professor_id JOIN usuarios up ON up.id=p.usuario_id JOIN alunos al ON al.id=ag.aluno_id JOIN usuarios u ON u.id=al.usuario_id ORDER BY ag.data DESC,a.horario DESC")->fetchAll();$titulo='Agendamentos';include __DIR__.'/../includes/header.php';
?>
<div class="mb-4"><h1 class="dashboard-title">Agendamentos</h1></div>
<div class="table-responsive fit-card p-3"><table class="table"><thead><tr><th>Aluno</th><th>Aula</th><th>Professor</th><th>Data</th><th>Status</th></tr></thead><tbody><?php foreach($ag as $x):?><tr><td><?=htmlspecialchars($x['aluno'])?></td><td><?=htmlspecialchars($x['aula'])?></td><td><?=htmlspecialchars($x['professor'])?></td><td><?=date('d/m/Y',strtotime($x['data']))?></td><td><?=htmlspecialchars($x['status'])?></td></tr><?php endforeach;?></tbody></table></div>
<?php include __DIR__.'/../includes/footer.php'; ?>

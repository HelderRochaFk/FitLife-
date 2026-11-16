<?php
require __DIR__.'/../config/conexao.php';require __DIR__.'/../includes/auth.php';exigirPerfil('admin');
$stats=[];
foreach(['usuarios WHERE perfil="aluno"','usuarios WHERE perfil="professor"','aulas','agendamentos'] as $i=>$q){$stats[$i]=$pdo->query("SELECT COUNT(*) FROM $q")->fetchColumn();}
$titulo='Painel administrativo';include __DIR__.'/../includes/header.php';
?>
<div class="mb-4"><span class="text-fit fw-bold">ADMINISTRAÇÃO</span><h1 class="dashboard-title">Visão geral</h1></div>
<div class="row g-3 mb-4"><?php $labels=['Alunos','Professores','Aulas','Agendamentos'];foreach($labels as $i=>$l): ?><div class="col-6 col-lg-3"><div class="stat-card"><div class="muted"><?=$l?></div><div class="stat-number"><?=$stats[$i]?></div></div></div><?php endforeach;?></div>
<div class="d-flex flex-wrap gap-2"><a class="btn btn-fit" href="/fitlife/admin/alunos.php">Gerenciar alunos</a><a class="btn btn-outline-light" href="/fitlife/admin/professores.php">Professores</a><a class="btn btn-outline-light" href="/fitlife/admin/aulas.php">Aulas</a><a class="btn btn-outline-light" href="/fitlife/admin/agendamentos.php">Agendamentos</a><a class="btn btn-outline-light" href="/fitlife/admin/pagamentos.php">Pagamentos de demonstração</a></div>
<?php include __DIR__.'/../includes/footer.php'; ?>

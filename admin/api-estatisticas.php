<?php
require __DIR__.'/../includes/auth.php'; exigirPerfil('admin');
$titulo='API C#'; include __DIR__.'/../includes/header.php';

$url='http://localhost:5080/api/estatisticas';
$ch=curl_init($url);
curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>3]);
$json=curl_exec($ch);
$http=curl_getinfo($ch,CURLINFO_HTTP_CODE);
curl_close($ch);
?>
<div class="mb-4"><span class="text-fit fw-bold">INTEGRAÇÃO C#</span><h1 class="dashboard-title">Estatísticas da API</h1><p class="muted">Dados fornecidos pela API ASP.NET Core.</p></div>
<?php if($json===false || $http!==200): ?>
<div class="alert alert-warning">A API C# não respondeu. Inicie a API em http://localhost:5080.</div>
<?php else: $d=json_decode($json,true); ?>
<div class="row g-3">
<?php foreach(['alunos'=>'Alunos','professores'=>'Professores','aulas'=>'Aulas','agendamentos'=>'Agendamentos confirmados'] as $k=>$label): ?>
<div class="col-md-3"><div class="stat-card"><div class="muted"><?=$label?></div><div class="stat-number"><?=htmlspecialchars((string)$d[$k])?></div></div></div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<?php include __DIR__.'/../includes/footer.php'; ?>

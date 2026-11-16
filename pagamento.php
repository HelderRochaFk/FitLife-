<?php
require __DIR__.'/config/conexao.php';
require __DIR__.'/includes/auth.php';
exigirLogin();
if (($_SESSION['usuario']['perfil'] ?? '') !== 'aluno') { http_response_code(403); exit('Esta etapa de contratação está disponível apenas para contas de aluno.'); }
$planos=['Essencial'=>89.90,'Performance'=>149.90,'Personal'=>249.90];
$plano=trim($_POST['plano'] ?? $_GET['plano'] ?? 'Performance');
if (!isset($planos[$plano])) $plano='Performance';
$erro=''; $sucesso='';
$st=$pdo->prepare('SELECT id FROM alunos WHERE usuario_id=?'); $st->execute([$_SESSION['usuario']['id']]); $alunoId=(int)$st->fetchColumn();
if ($_SERVER['REQUEST_METHOD']==='POST') {
  validarCsrf();
  $metodo=$_POST['metodo'] ?? '';
  if (!in_array($metodo,['pix','cartao_demo','presencial'],true)) $erro='Escolha uma forma de pagamento válida.';
  elseif (!$alunoId) $erro='Não foi possível localizar o cadastro de aluno.';
  else {
    $s=$pdo->prepare("INSERT INTO pagamentos(aluno_id,plano,valor,metodo,status) VALUES(?,?,?,?, 'demonstracao')");
    $s->execute([$alunoId,$plano,$planos[$plano],$metodo]);
    $sucesso='Solicitação registrada com sucesso!';
  }
}
$titulo='Finalizar contratação'; include __DIR__.'/includes/header.php';
?>
<div class="row justify-content-center py-3"><div class="col-lg-8"><div class="fit-card p-4 p-md-5">
<span class="eyebrow">ETAPA DE CONTRATAÇÃO</span><h1 class="dashboard-title mt-2">Quase lá.</h1><p class="muted">Confira seu plano e escolha a forma de pagamento desejada.</p>
<?php if($erro): ?><div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<?php if($sucesso): ?><div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div><a class="btn btn-fit" href="/fitlife/aluno/dashboard.php">Ir para meu painel</a><?php else: ?>
<form method="post" class="row g-3"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars(tokenCsrf(),ENT_QUOTES,'UTF-8') ?>">
<div class="col-12"><label class="form-label" for="plano">Plano</label><select class="form-select" id="plano" name="plano" onchange="document.getElementById('valor-plano').textContent=this.options[this.selectedIndex].dataset.valor"><?php foreach($planos as $nome=>$valor): ?><option value="<?= htmlspecialchars($nome) ?>" data-valor="R$ <?= number_format($valor,2,',','.') ?>" <?= $plano===$nome?'selected':'' ?>><?= htmlspecialchars($nome) ?> — R$ <?= number_format($valor,2,',','.') ?>/mês</option><?php endforeach; ?></select></div>
<div class="col-12"><div class="fit-card p-3"><div class="muted small">VALOR MENSAL</div><div id="valor-plano" class="plan-price">R$ <?= number_format($planos[$plano],2,',','.') ?></div></div></div>
<div class="col-12"><label class="form-label" for="metodo">Forma de pagamento</label><select class="form-select" name="metodo" id="metodo" required><option value="pix">PIX</option><option value="cartao_demo">Cartão</option><option value="presencial">Pagamento presencial</option></select></div>
<div class="col-12 d-flex flex-wrap gap-2"><button class="btn btn-fit btn-lg">Confirmar contratação</button><a class="btn btn-outline-light btn-lg" href="/fitlife/planos.php">Voltar aos planos</a></div>
</form><?php endif; ?></div></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>

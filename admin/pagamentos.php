<?php
require __DIR__.'/../config/conexao.php'; require __DIR__.'/../includes/auth.php'; exigirPerfil('admin');
$pagamentos=$pdo->query('SELECT pg.id,pg.plano,pg.valor,pg.metodo,pg.status,pg.criado_em,u.nome AS aluno,u.email FROM pagamentos pg JOIN alunos a ON a.id=pg.aluno_id JOIN usuarios u ON u.id=a.usuario_id ORDER BY pg.criado_em DESC')->fetchAll();
$titulo='Pagamentos'; include __DIR__.'/../includes/header.php';
?>
<div class="mb-4"><span class="eyebrow">ADMINISTRAÇÃO</span><h1 class="dashboard-title mt-2">Registros de contratação</h1><p class="muted">Histórico de solicitações de contratação realizadas na área de planos.</p></div>
<div class="table-responsive fit-card p-3"><table class="table align-middle"><thead><tr><th>Aluno</th><th>Plano</th><th>Valor</th><th>Método escolhido</th><th>Status</th><th>Data</th></tr></thead><tbody>
<?php if(!$pagamentos): ?><tr><td colspan="6" class="muted text-center py-4">Nenhuma contratação registrada até agora.</td></tr><?php endif; ?>
<?php foreach($pagamentos as $pg): ?><tr><td><?=htmlspecialchars($pg['aluno'])?><div class="muted small"><?=htmlspecialchars($pg['email'])?></div></td><td><?=htmlspecialchars($pg['plano'])?></td><td>R$ <?=number_format((float)$pg['valor'],2,',','.')?></td><td><?=htmlspecialchars(['pix'=>'PIX','cartao_demo'=>'Cartão','presencial'=>'Presencial'][$pg['metodo']]??$pg['metodo'])?></td><td><span class="status-pill"><?= htmlspecialchars(ucfirst($pg['status'])) ?></span></td><td><?=date('d/m/Y H:i',strtotime($pg['criado_em']))?></td></tr><?php endforeach; ?>
</tbody></table></div>
<?php include __DIR__.'/../includes/footer.php'; ?>

<?php
$titulo='Planos';
include __DIR__.'/includes/header.php';
$planos=[
 ['nome'=>'Essencial','valor'=>89.90,'periodo'=>'mês','descricao'=>'Para criar uma rotina consistente.','itens'=>['Acesso às aulas em grupo','Agenda online de aulas','Acompanhamento da frequência'],'destaque'=>false],
 ['nome'=>'Performance','valor'=>149.90,'periodo'=>'mês','descricao'=>'Para quem quer ir além com orientação.','itens'=>['Tudo do Essencial','Treino personalizado','Revisão mensal de objetivos','Prioridade em novas turmas'],'destaque'=>true],
 ['nome'=>'Personal','valor'=>249.90,'periodo'=>'mês','descricao'=>'Mais acompanhamento, no seu ritmo.','itens'=>['Tudo do Performance','Sessão individual mensal','Acompanhamento próximo do professor','Ajustes de treino individualizados'],'destaque'=>false],
];
?>
<section class="py-4 py-lg-5"><div class="text-center mx-auto mb-5" style="max-width:720px"><span class="eyebrow">PLANOS FITLIFE</span><h1 class="display-4 fw-bold mt-3">Seu objetivo merece <span class="text-fit">um plano.</span></h1><p class="muted lead mt-3">Encontre o plano ideal para sua rotina, seus objetivos e seu ritmo de evolução.</p></div>
<div class="row g-4 align-items-stretch">
<?php foreach($planos as $plano): ?><div class="col-md-6 col-lg-4"><article class="fit-card plan-card <?= $plano['destaque']?'featured':'' ?>">
<?php if($plano['destaque']): ?><span class="badge badge-fit mb-3">MAIS ESCOLHIDO</span><?php endif; ?>
<div class="eyebrow"><?= htmlspecialchars(strtoupper($plano['nome'])) ?></div><h2 class="h3 mt-2"><?= htmlspecialchars($plano['descricao']) ?></h2>
<div class="plan-price mt-4">R$ <?= number_format($plano['valor'],2,',','.') ?><small>/<?= $plano['periodo'] ?></small></div>
<div class="border-top border-secondary my-4"></div><?php foreach($plano['itens'] as $item): ?><div class="plan-feature"><?= htmlspecialchars($item) ?></div><?php endforeach; ?>
<a class="btn <?= $plano['destaque']?'btn-fit':'btn-outline-fit' ?> w-100 mt-4" href="<?= isset($_SESSION['usuario']) && ($_SESSION['usuario']['perfil'] ?? '') === 'aluno' ? '/fitlife/pagamento.php?plano=' . urlencode($plano['nome']) : '/fitlife/login.php' ?>">Escolher plano</a>
</article></div><?php endforeach; ?>
</div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>

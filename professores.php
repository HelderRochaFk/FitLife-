<?php
require __DIR__ . '/config/conexao.php';
$titulo = 'Professores';
$professores = $pdo->query("SELECT p.*, u.nome FROM professores p JOIN usuarios u ON u.id = p.usuario_id ORDER BY u.nome")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<section class="professores-intro mb-5 reveal">
  <span class="text-fit fw-bold text-uppercase small letter-spaced">Nossa equipe</span>
  <h1 class="dashboard-title display-5 mt-2">Quem vai acompanhar <span class="text-fit">sua evolução.</span></h1>
  <p class="muted lead mb-0">Conheça os profissionais que ajudam você a treinar com estratégia, segurança e consistência.</p>
</section>
<div class="row g-4">
<?php foreach ($professores as $i => $p): ?>
  <?php
    $foto = !empty($p['imagem']) ? $p['imagem'] : '';
    $nomeNormalizado = mb_strtolower($p['nome'], 'UTF-8');
    if (!$foto && str_contains($nomeNormalizado, 'marcio')) $foto = 'assets/professores/marcio-klein.jpg';
    if (!$foto && str_contains($nomeNormalizado, 'márcio')) $foto = 'assets/professores/marcio-klein.jpg';
    if (!$foto && str_contains($nomeNormalizado, 'daniel')) $foto = 'assets/professores/daniel-ferreira.jpg';
    if (!$foto && str_contains($nomeNormalizado, 'marcio romero')) $foto = 'assets/professores/marcio-romero.jpg';
    if (!$foto && str_contains($nomeNormalizado, 'márcio romero')) $foto = 'assets/professores/marcio-romero.jpg';
    $fotoExiste = $foto && is_file(__DIR__ . '/' . $foto);
  ?>
  <div class="col-md-6 col-lg-4 reveal" style="--reveal-delay: <?= (int)($i % 3) * 100 ?>ms">
    <article class="fit-card professor-card h-100 overflow-hidden">
      <?php if ($fotoExiste): ?>
        <img class="professor-photo" src="/fitlife/<?= htmlspecialchars($foto, ENT_QUOTES, 'UTF-8') ?>" alt="Foto de <?= htmlspecialchars($p['nome'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
      <?php else: ?>
        <div class="professor-photo professor-placeholder" aria-label="Foto ainda não cadastrada"><span><?= htmlspecialchars(mb_strtoupper(mb_substr($p['nome'], 0, 1, 'UTF-8'), 'UTF-8')) ?></span></div>
      <?php endif; ?>
      <div class="p-4">
        <span class="professor-label">PROFISSIONAL FITLIFE</span>
        <h2 class="h3 mt-2 mb-2"><?= htmlspecialchars($p['nome']) ?></h2>
        <div class="text-fit fw-semibold mb-3"><?= htmlspecialchars($p['especialidade'] ?? '') ?></div>
        <p class="muted mb-3"><?= htmlspecialchars($p['bio'] ?? '') ?></p><?php if (!empty($p['telefone'])): ?><a class="professor-contact" href="https://wa.me/55<?= preg_replace('/\D/', '', $p['telefone']) ?>" target="_blank" rel="noopener noreferrer">WhatsApp do professor ↗</a><?php endif; ?>
      </div>
    </article>
  </div>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>

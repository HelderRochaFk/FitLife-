<nav class="navbar navbar-expand-lg navbar-dark fit-navbar">
  <div class="container">
    <a class="navbar-brand fit-brand" href="/fitlife/index.php" aria-label="Fitlife Studio - início">
      <img src="/fitlife/assets/logo-fitlife.jpg" alt="Fitlife Studio" class="fit-logo">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Abrir menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <li class="nav-item"><a class="nav-link" href="/fitlife/index.php">Início</a></li>
        <li class="nav-item"><a class="nav-link" href="/fitlife/aulas.php">Aulas</a></li>
        <li class="nav-item"><a class="nav-link" href="/fitlife/professores.php">Professores</a></li>
        <li class="nav-item"><a class="nav-link" href="/fitlife/planos.php">Planos</a></li>
        <?php if (isset($_SESSION['usuario'])): ?>
          <?php $p = $_SESSION['usuario']['perfil']; ?>
          <li class="nav-item"><a class="btn btn-fit ms-lg-2" href="/fitlife/<?= htmlspecialchars($p, ENT_QUOTES, 'UTF-8') ?>/dashboard.php">Meu painel</a></li>
          <li class="nav-item"><a class="nav-link" href="/fitlife/logout.php">Sair</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/fitlife/login.php">Entrar</a></li>
          <li class="nav-item"><a class="btn btn-fit ms-lg-2" href="/fitlife/cadastro.php">Criar conta</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

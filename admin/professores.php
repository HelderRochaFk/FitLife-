<?php
require __DIR__.'/../config/conexao.php';
require __DIR__.'/../includes/auth.php';
exigirPerfil('admin');
$erro = '';
$msg = '';
$uploadDir = __DIR__ . '/../uploads/professores/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $especialidade = trim($_POST['especialidade'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $imagemRelativa = null;
    try {
        if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8) {
            throw new RuntimeException('Informe nome, e-mail válido e senha com pelo menos 8 caracteres.');
        }
        if (!empty($_FILES['imagem']['name'])) {
            if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Não foi possível receber a imagem enviada.');
            if ($_FILES['imagem']['size'] > 3 * 1024 * 1024) throw new RuntimeException('A imagem deve ter no máximo 3 MB.');
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['imagem']['tmp_name']);
            $extensoes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            if (!isset($extensoes[$mime]) || @getimagesize($_FILES['imagem']['tmp_name']) === false) {
                throw new RuntimeException('Envie uma imagem JPG, PNG ou WEBP válida.');
            }
            $nomeArquivo = bin2hex(random_bytes(16)) . '.' . $extensoes[$mime];
            if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadDir . $nomeArquivo)) {
                throw new RuntimeException('Não foi possível salvar a imagem. Confira as permissões da pasta uploads.');
            }
            $imagemRelativa = 'uploads/professores/' . $nomeArquivo;
        }
        $pdo->beginTransaction();
        $s = $pdo->prepare("INSERT INTO usuarios(nome,email,senha,perfil) VALUES(?,?,?,'professor')");
        $s->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT)]);
        $uid = $pdo->lastInsertId();
        $s = $pdo->prepare("INSERT INTO professores(usuario_id,especialidade,telefone,bio,imagem) VALUES(?,?,?,?,?)");
        $s->execute([$uid, $especialidade, $telefone, $bio, $imagemRelativa]);
        $pdo->commit();
        $msg = 'Professor cadastrado com sucesso.';
    } catch (RuntimeException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        if (!empty($imagemRelativa) && is_file(__DIR__ . '/../' . $imagemRelativa)) unlink(__DIR__ . '/../' . $imagemRelativa);
        $erro = $e->getMessage();
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        if (!empty($imagemRelativa) && is_file(__DIR__ . '/../' . $imagemRelativa)) unlink(__DIR__ . '/../' . $imagemRelativa);
        $erro = 'Não foi possível cadastrar. Confira se o e-mail já está sendo utilizado e se a atualização do banco foi aplicada.';
    }
}
$prof = $pdo->query("SELECT p.id,u.nome,u.email,p.especialidade,p.telefone,p.bio,p.imagem FROM professores p JOIN usuarios u ON u.id=p.usuario_id ORDER BY u.nome")->fetchAll();
$titulo='Professores';
include __DIR__.'/../includes/header.php';
?>
<div class="mb-4"><span class="text-fit fw-bold text-uppercase small letter-spaced">Gestão da equipe</span><h1 class="dashboard-title mt-2">Professores</h1><p class="muted">Cadastre profissionais e adicione uma foto para a página pública.</p></div>
<?php if($msg): ?><div class="alert alert-success"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<?php if($erro): ?><div class="alert alert-danger"><?=htmlspecialchars($erro)?></div><?php endif; ?>
<div class="fit-card p-4 mb-4"><h2 class="h4 mb-3">Novo professor</h2><form method="post" enctype="multipart/form-data" class="row g-3">
<div class="col-md-6"><label class="form-label" for="nome">Nome completo</label><input class="form-control" id="nome" name="nome" maxlength="120" required></div>
<div class="col-md-6"><label class="form-label" for="email">E-mail de acesso</label><input class="form-control" id="email" type="email" name="email" maxlength="160" required></div>
<div class="col-md-4"><label class="form-label" for="senha">Senha inicial</label><input class="form-control" id="senha" type="password" name="senha" minlength="8" autocomplete="new-password" required><div class="form-text text-secondary">Mínimo de 8 caracteres.</div></div>
<div class="col-md-4"><label class="form-label" for="especialidade">Especialidade</label><input class="form-control" id="especialidade" name="especialidade" maxlength="120" placeholder="Ex.: Musculação e hipertrofia"></div>
<div class="col-md-4"><label class="form-label" for="telefone">Telefone profissional</label><input class="form-control" id="telefone" name="telefone" maxlength="30" placeholder="(11) 99876-5432"></div>
<div class="col-12"><label class="form-label" for="bio">Biografia</label><textarea class="form-control" id="bio" name="bio" rows="3" maxlength="2000" placeholder="Conte um pouco sobre a experiência do profissional"></textarea></div>
<div class="col-12"><label class="form-label" for="imagem">Foto do professor (opcional)</label><input class="form-control" id="imagem" type="file" name="imagem" accept="image/jpeg,image/png,image/webp"><div class="form-text text-secondary">JPG, PNG ou WEBP. Máximo de 3 MB. Se não enviar agora, poderá adicionar depois.</div></div>
<div class="col-12"><button class="btn btn-fit px-4">Cadastrar professor</button></div></form></div>
<div class="table-responsive fit-card p-3"><table class="table align-middle"><thead><tr><th>Professor</th><th>E-mail</th><th>Especialidade</th><th>Telefone</th><th>Foto</th></tr></thead><tbody><?php foreach($prof as $p): ?><tr><td><?=htmlspecialchars($p['nome'])?></td><td><?=htmlspecialchars($p['email'])?></td><td><?=htmlspecialchars($p['especialidade'] ?? '')?></td><td><?=htmlspecialchars($p['telefone'] ?? '')?></td><td><?=$p['imagem'] ? 'Cadastrada' : 'Pendente'?></td></tr><?php endforeach; ?></tbody></table></div>
<?php include __DIR__.'/../includes/footer.php'; ?>

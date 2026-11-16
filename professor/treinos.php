<?php
require __DIR__.'/../config/conexao.php';require __DIR__.'/../includes/auth.php';exigirPerfil('professor');
$stmt=$pdo->prepare("SELECT id FROM professores WHERE usuario_id=?");$stmt->execute([$_SESSION['usuario']['id']]);$pid=$stmt->fetchColumn();
$msg='';$erro='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $aluno=(int)$_POST['aluno_id'];$tituloT=trim($_POST['titulo']);$desc=trim($_POST['descricao']);
 if($aluno && $tituloT && $desc){$pdo->prepare("INSERT INTO treinos(aluno_id,professor_id,titulo,descricao) VALUES(?,?,?,?)")->execute([$aluno,$pid,$tituloT,$desc]);$msg='Treino criado.';}else{$erro='Preencha todos os campos.';}
}
$stmt=$pdo->prepare("SELECT DISTINCT al.id,u.nome FROM alunos al JOIN usuarios u ON u.id=al.usuario_id JOIN agendamentos ag ON ag.aluno_id=al.id JOIN aulas au ON au.id=ag.aula_id WHERE au.professor_id=? ORDER BY u.nome");$stmt->execute([$pid]);$alunos=$stmt->fetchAll();
$stmt=$pdo->prepare("SELECT t.titulo,t.descricao,u.nome aluno FROM treinos t JOIN alunos al ON al.id=t.aluno_id JOIN usuarios u ON u.id=al.usuario_id WHERE t.professor_id=? ORDER BY t.criado_em DESC");$stmt->execute([$pid]);$treinos=$stmt->fetchAll();
$titulo='Treinos';include __DIR__.'/../includes/header.php';
?>
<div class="mb-4"><span class="text-fit fw-bold">PERSONALIZAÇÃO</span><h1 class="dashboard-title">Treinos</h1></div>
<?php if($msg): ?><div class="alert alert-success"><?=$msg?></div><?php endif;?><?php if($erro): ?><div class="alert alert-danger"><?=$erro?></div><?php endif;?>
<div class="fit-card p-4 mb-4"><h4>Novo treino</h4><form method="post" class="row g-3"><div class="col-md-4"><label class="form-label">Aluno</label><select class="form-select" name="aluno_id" required><option value="">Selecione</option><?php foreach($alunos as $a): ?><option value="<?=$a['id']?>"><?=htmlspecialchars($a['nome'])?></option><?php endforeach;?></select></div><div class="col-md-4"><label class="form-label">Título</label><input class="form-control" name="titulo" required></div><div class="col-md-12"><label class="form-label">Descrição / exercícios</label><textarea class="form-control" name="descricao" rows="4" required></textarea></div><div><button class="btn btn-fit">Criar treino</button></div></form></div>
<div class="row g-3"><?php foreach($treinos as $t): ?><div class="col-md-6"><div class="fit-card p-4"><h5><?=htmlspecialchars($t['titulo'])?></h5><div class="text-fit"><?=htmlspecialchars($t['aluno'])?></div><p class="muted mt-2"><?=nl2br(htmlspecialchars($t['descricao']))?></p></div></div><?php endforeach;?></div>
<?php include __DIR__.'/../includes/footer.php'; ?>

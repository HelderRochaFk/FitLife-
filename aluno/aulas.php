<?php
require __DIR__.'/../config/conexao.php';
require __DIR__.'/../includes/auth.php';
exigirPerfil('aluno');
$uid=(int)$_SESSION['usuario']['id'];
$s=$pdo->prepare('SELECT id FROM alunos WHERE usuario_id=?'); $s->execute([$uid]); $alunoId=(int)$s->fetchColumn();
$msg=''; $erro='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    validarCsrf();
    if (isset($_POST['cancelar_id'])) {
        $id=(int)$_POST['cancelar_id'];
        $s=$pdo->prepare("UPDATE agendamentos SET status='cancelado' WHERE id=? AND aluno_id=? AND status='confirmado' AND data>=CURDATE()");
        $s->execute([$id,$alunoId]);
        $msg=$s->rowCount()?'Agendamento cancelado com sucesso.':'Não foi possível cancelar esse agendamento.';
    } else {
        $aula=(int)($_POST['aula_id']??0); $data=trim($_POST['data']??'');
        $dataValida=DateTime::createFromFormat('Y-m-d',$data);
        if (!$alunoId) $erro='Não foi possível localizar seu cadastro de aluno.';
        elseif (!$dataValida || $dataValida->format('Y-m-d')!==$data || $data<date('Y-m-d')) $erro='Escolha uma data válida a partir de hoje.';
        else {
            try {
                $pdo->beginTransaction();
                $s=$pdo->prepare('SELECT id, vagas, dia_semana, nome FROM aulas WHERE id=? FOR UPDATE'); $s->execute([$aula]); $info=$s->fetch();
                if (!$info) throw new RuntimeException('A aula selecionada não existe.');
                $diaEscolhido=(int)date('N',strtotime($data));
                if ($diaEscolhido!==(int)$info['dia_semana']) throw new RuntimeException('Essa aula não acontece no dia da semana escolhido. Confira o dia indicado no cartão.');
                $s=$pdo->prepare("SELECT COUNT(*) FROM agendamentos WHERE aula_id=? AND data=? AND status='confirmado'"); $s->execute([$aula,$data]);
                if ((int)$s->fetchColumn()>=(int)$info['vagas']) throw new RuntimeException('As vagas para essa aula e data já estão preenchidas.');
                $s=$pdo->prepare("SELECT id,status FROM agendamentos WHERE aluno_id=? AND aula_id=? AND data=? LIMIT 1 FOR UPDATE"); $s->execute([$alunoId,$aula,$data]); $existente=$s->fetch();
                if ($existente && $existente['status']==='confirmado') throw new RuntimeException('Você já possui uma reserva ativa para essa aula nessa data.');
                if ($existente) { $s=$pdo->prepare("UPDATE agendamentos SET status='confirmado' WHERE id=?"); $s->execute([(int)$existente['id']]); }
                else { $s=$pdo->prepare("INSERT INTO agendamentos(aluno_id,aula_id,data,status) VALUES(?,?,?,'confirmado')"); $s->execute([$alunoId,$aula,$data]); }
                $pdo->commit(); $msg='Reserva confirmada! Você já pode acompanhar o agendamento nesta página.';
            } catch (RuntimeException $e) { if($pdo->inTransaction()) $pdo->rollBack(); $erro=$e->getMessage(); }
              catch (PDOException $e) { if($pdo->inTransaction()) $pdo->rollBack(); $erro='Não foi possível concluir a reserva. Atualize a página e tente novamente.'; }
        }
    }
}
$aulas=$pdo->query('SELECT a.*,u.nome AS professor FROM aulas a JOIN professores p ON p.id=a.professor_id JOIN usuarios u ON u.id=p.usuario_id ORDER BY a.dia_semana,a.horario')->fetchAll();
$s=$pdo->prepare('SELECT ag.id,ag.data,ag.status,a.nome AS aula,a.horario,a.duracao_min,u.nome AS professor FROM agendamentos ag JOIN aulas a ON a.id=ag.aula_id JOIN professores p ON p.id=a.professor_id JOIN usuarios u ON u.id=p.usuario_id WHERE ag.aluno_id=? ORDER BY ag.data DESC,a.horario'); $s->execute([$alunoId]); $meus=$s->fetchAll();
$dia=[1=>'Segunda-feira',2=>'Terça-feira',3=>'Quarta-feira',4=>'Quinta-feira',5=>'Sexta-feira',6=>'Sábado',7=>'Domingo'];
$titulo='Agenda de aulas'; include __DIR__.'/../includes/header.php';
?>
<section class="mb-4"><span class="eyebrow">AGENDA PERSONALIZADA</span><h1 class="dashboard-title display-5 mt-2">Escolha seu próximo <span class="text-fit">treino.</span></h1><p class="muted">Selecione uma aula e uma data compatível com o dia da semana dela. Sua reserva aparecerá em “Minhas reservas”.</p></section>
<?php if($msg): ?><div class="alert alert-success"><?=htmlspecialchars($msg)?></div><?php endif; ?><?php if($erro): ?><div class="alert alert-danger"><?=htmlspecialchars($erro)?></div><?php endif; ?>
<div class="row g-4 mb-5">
<?php foreach($aulas as $a): ?><div class="col-md-6 col-xl-4"><article class="fit-card booking-card p-4 h-100"><div class="d-flex justify-content-between align-items-start gap-2"><span class="badge badge-fit"><?=htmlspecialchars($dia[(int)$a['dia_semana']]??'Dia a confirmar')?></span><span class="status-pill"><?= (int)$a['vagas'] ?> vagas totais</span></div><h2 class="h4 mt-3"><?=htmlspecialchars($a['nome'])?></h2><p class="muted"><?=htmlspecialchars($a['descricao']??'')?></p><div class="small muted mb-1">Professor</div><div class="fw-semibold"><?=htmlspecialchars($a['professor'])?></div><div class="d-flex flex-wrap gap-3 mt-3 mb-4 text-light small"><span>◷ <?=date('H:i',strtotime($a['horario']))?></span><span>⌁ <?= (int)$a['duracao_min'] ?> minutos</span></div>
<form method="post" class="mt-auto"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(tokenCsrf(),ENT_QUOTES,'UTF-8')?>"><input type="hidden" name="aula_id" value="<?=(int)$a['id']?>"><label class="form-label" for="data-<?=(int)$a['id']?>">Data da aula</label><input class="form-control mb-3" id="data-<?=(int)$a['id']?>" type="date" name="data" min="<?=date('Y-m-d')?>" required><button class="btn btn-fit w-100">Reservar minha vaga</button></form></article></div><?php endforeach; ?>
</div>
<section><div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3"><div><span class="eyebrow">SEU HISTÓRICO</span><h2 class="h2 mt-2">Minhas reservas</h2></div><span class="muted small">Você pode cancelar reservas futuras.</span></div>
<div class="table-responsive fit-card p-3"><table class="table align-middle mb-0"><thead><tr><th>Aula</th><th>Professor</th><th>Data e horário</th><th>Status</th><th>Ação</th></tr></thead><tbody>
<?php if(!$meus): ?><tr><td colspan="5" class="muted py-4 text-center">Você ainda não tem reservas. Escolha uma aula acima para começar.</td></tr><?php endif; ?>
<?php foreach($meus as $m): ?><tr><td><?=htmlspecialchars($m['aula'])?></td><td><?=htmlspecialchars($m['professor'])?></td><td><?=date('d/m/Y',strtotime($m['data']))?> às <?=date('H:i',strtotime($m['horario']))?></td><td><span class="status-pill"><?=htmlspecialchars(ucfirst($m['status']))?></span></td><td><?php if($m['status']==='confirmado' && $m['data']>=date('Y-m-d')): ?><form method="post" onsubmit="return confirm('Deseja cancelar esta reserva?')"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(tokenCsrf(),ENT_QUOTES,'UTF-8')?>"><input type="hidden" name="cancelar_id" value="<?=(int)$m['id']?>"><button class="btn btn-sm btn-outline-danger">Cancelar</button></form><?php else: ?><span class="muted">—</span><?php endif; ?></td></tr><?php endforeach; ?>
</tbody></table></div></section>
<?php include __DIR__.'/../includes/footer.php'; ?>

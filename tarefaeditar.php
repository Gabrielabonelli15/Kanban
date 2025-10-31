<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
include 'db.php'; include 'usuario_listar.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Editar Tarefa</h2>
    <div class="menu">
        <a href="index.php">Menu Principal</a>
        <a href="kanban.php">Voltar ao Kanban</a>
    </div>
    <?php
    $msg = '';
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $tarefa = null;
    if ($id) {
        $result = $conn->query("SELECT * FROM tarefa WHERE id=$id");
        $tarefa = $result->fetch_assoc();
    }
    if (!$tarefa) {
        echo '<p class="error">Tarefa não encontrada.</p>';
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_usuario = intval($_POST['id_usuario']);
        $descricao = trim($_POST['descricao']);
        $setor = trim($_POST['setor']);
        $prioridade = $_POST['prioridade'];
        $status = $_POST['status'];
        if ($id_usuario && $descricao && $setor && in_array($prioridade, ['baixa','media','alta']) && in_array($status, ['a_fazer','fazendo','pronto'])) {
            $stmt = $conn->prepare("UPDATE tarefa SET id_usuario=?, descricao=?, setor=?, prioridade=?, status=? WHERE id=?");
            $stmt->bind_param('issssi', $id_usuario, $descricao, $setor, $prioridade, $status, $id);
            if ($stmt->execute()) {
                $msg = '<p class="success">Tarefa atualizada com sucesso</p>';
                $tarefa = array_merge($tarefa, $_POST);
            } else {
                $msg = '<p class="error">Erro ao atualizar: ' . $conn->error . '</p>';
            }
            $stmt->close();
        } else {
            $msg = '<p class="error">Preencha todos os campos corretamente.</p>';
        }
    }
    echo $msg;
    ?>
    <form method="post">
        <label>Usuário:</label>
        <select name="id_usuario" required>
            <?php foreach ($usuarios as $u) { $sel = $u['id']==$tarefa['id_usuario']?'selected':''; echo "<option value='{$u['id']}' $sel>{$u['nome']}</option>"; } ?>
        </select>
        <label>Descrição:</label>
        <textarea name="descricao" required><?= htmlspecialchars($tarefa['descricao']) ?></textarea>
        <label>Setor:</label>
        <input type="text" name="setor" value="<?= htmlspecialchars($tarefa['setor']) ?>" required>
        <label>Prioridade:</label>
        <select name="prioridade" required>
            <option value="baixa" <?= $tarefa['prioridade']=='baixa'?'selected':'' ?>>Baixa</option>
            <option value="media" <?= $tarefa['prioridade']=='media'?'selected':'' ?>>Média</option>
            <option value="alta" <?= $tarefa['prioridade']=='alta'?'selected':'' ?>>Alta</option>
        </select>
        <label>Status:</label>
        <select name="status" required>
            <option value="a_fazer" <?= $tarefa['status']=='a_fazer'?'selected':'' ?>>A Fazer</option>
            <option value="fazendo" <?= $tarefa['status']=='fazendo'?'selected':'' ?>>Fazendo</option>
            <option value="pronto" <?= $tarefa['status']=='pronto'?'selected':'' ?>>Pronto</option>
        </select>
        <button type="submit">Salvar Alterações</button>
    </form>
</div>
</body>
</html>
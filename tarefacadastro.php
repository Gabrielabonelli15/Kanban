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
    <title>Cadastro de Tarefas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Cadastro de Tarefas</h2>
    <div class="menu">
        <a href="index.php">Menu Principal</a>
    </div>
    <?php
    $msg = ''; 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_usuario = intval($_POST['id_usuario']);
        $descricao = trim($_POST['descricao']);
        $setor = trim($_POST['setor']); 
        $prioridade = $_POST['prioridade'];
        if ($id_usuario && $descricao && $setor && in_array($prioridade, ['baixa','media','alta'])) {
            $stmt = $conn->prepare("INSERT INTO tarefa (id_usuario, descricao, setor, prioridade) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('isss', $id_usuario, $descricao, $setor, $prioridade);
            if ($stmt->execute()) {
                $msg = '<p class="success">Cadastro concluído com sucesso</p>';
            } else {
                $msg = '<p class="error">Erro ao cadastrar: ' . $conn->error . '</p>';
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
            <option value="">Selecione</option>
            <?php foreach ($usuarios as $u) { echo "<option value='{$u['id']}'>{$u['nome']}</option>"; } ?>
        </select>
        <label>Descrição:</label>
        <textarea name="descricao" required></textarea>
        <label>Setor:</label>
        <input type="text" name="setor" required>
        <label>Prioridade:</label>
        <select name="prioridade" required>
            <option value="baixa">Baixa</option>
            <option value="media">Média</option>
            <option value="alta">Alta</option>
        </select>
        <button type="submit">Cadastrar</button>
    </form>
</div>
</body>
</html>
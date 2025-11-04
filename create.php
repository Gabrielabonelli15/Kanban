<?php
require_once 'db.php';
$msg = '';
if (isset($_POST['enviar'])) {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($nome && $email) {
        $sql = "INSERT INTO usuario (nome, email) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $nome, $email);
        if ($stmt->execute()) {
            $msg = '<div class="alert-sucesso">Usuário cadastrado!</div>';
        } else if ($conn->errno == 1062) {
            $msg = '<div class="alert-erro">E-mail já cadastrado.</div>';
        } else {
            $msg = '<div class="alert-erro">Erro: ' . htmlspecialchars($conn->error) . '</div>';
        }
    } else {
        $msg = '<div class="alert-erro">Preencha todos os campos.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Novo Usuário</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="box-cadastro">
        <h2>Novo Usuário</h2>
        <?php echo $msg; ?>
        <form method="post" autocomplete="off">
            <label for="nome">Nome completo</label>
            <input type="text" id="nome" name="nome" maxlength="100" required>
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" maxlength="100" required>
            <button class="btn-cadastrar" type="submit" name="enviar">Salvar</button>
        </form>
        <div style="text-align:center; margin-top:18px;">
            <a href="listausuario.php">Ver usuários</a>
        </div>
    </div>
</body>
</html>
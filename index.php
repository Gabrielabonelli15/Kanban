<?php
include 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    if ($email && $senha) {
        $stmt = $conn->prepare("SELECT id, nome, senha FROM usuario WHERE email=?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $nome, $senha_hash);
            $stmt->fetch();
            if (password_verify($senha, $senha_hash)) {
                $_SESSION['usuario_id'] = $id;
                $_SESSION['usuario_nome'] = $nome;
                header('Location: kanban.php');
                exit;
            } else {
                $msg = '<p class="error">Senha incorreta.</p>';
            }
        } else {
            $msg = '<p class="error">Usuário não encontrado.</p>';
        }
        $stmt->close();
    } else {
        $msg = '<p class="error">Preencha todos os campos.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <div class="menu">
        <a href="cadastrousuario.php">Cadastrar Usuário</a>
    </div>
    <?= $msg ?>
    <form method="post">
        <label>E-mail:</label>
        <input type="email" name="email" required>
        <label>Senha:</label>
        <input type="password" name="senha" required>
        <button type="submit">Entrar</button>
    </form>
</div>
</body>
</html>
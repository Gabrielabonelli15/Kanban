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
    <link rel="stylesheet" href="style.css">
    <style>
        .box-cadastro { max-width:340px; margin:60px auto; background:#f7f7f7; border-radius:10px; box-shadow:0 2px 8px #0001; padding:32px 28px; }
        .box-cadastro label { display:block; margin-bottom:6px; color:#222; font-weight:500; }
        .box-cadastro input { width:100%; padding:8px 10px; margin-bottom:18px; border:1px solid #bbb; border-radius:4px; }
        .btn-cadastrar { background:#009688; color:#fff; border:none; padding:10px 0; width:100%; border-radius:4px; font-size:1.1em; cursor:pointer; transition:.2s; }
        .btn-cadastrar:hover { background:#00796b; }
        .alert-sucesso { background:#c8e6c9; color:#256029; padding:10px; border-radius:4px; margin-bottom:18px; text-align:center; }
        .alert-erro { background:#ffcdd2; color:#b71c1c; padding:10px; border-radius:4px; margin-bottom:18px; text-align:center; }
    </style>
</head>
<body>
    <div class="box-cadastro">
        <h2 style="text-align:center; margin-bottom:24px;">Novo Usuário</h2>
        <?php echo $msg; ?>
        <form method="post" autocomplete="off">
            <label for="nome">Nome completo</label>
            <input type="text" id="nome" name="nome" maxlength="100" required>
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" maxlength="100" required>
            <button class="btn-cadastrar" type="submit" name="enviar">Salvar</button>
        </form>
        <div style="text-align:center; margin-top:18px;">
            <a href="read.php" style="color:#009688; text-decoration:none;">Ver usuários</a>
        </div>
    </div>
</body>
</html>
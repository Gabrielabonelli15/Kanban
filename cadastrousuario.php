 <?php
    include 'db.php';

        $msg = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome']);
            $email = trim($_POST['email']);
            $senha = $_POST['senha'];
            if ($nome && filter_var($email, FILTER_VALIDATE_EMAIL) && $senha) {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
                $stmt->bind_param('sss', $nome, $email, $senha_hash);
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
        <!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuários</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
    <h2>Cadastro de Usuário</h2>
    <div class="menu">
        <a href="login.php">Login</a>
        <a href="kanban.php">Kanban</a>
    </div>

   

    <form method="post">
        <label>Nome:</label>
        <input type="text" name="nome" required>
        <label>E-mail:</label>
        <input type="email" name="email" required>
        <label>Senha:</label>
        <input type="password" name="senha" required>
        <button type="submit">Cadastrar</button>
    </form>
</div>

</body>
</html>
<?php
include 'db.php';

$mensagem = ""; 

if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    try {
        $sql = "INSERT INTO usuario (nome, email) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nome, $email);
        $stmt->execute();
        $mensagem = "Usuário cadastrado com sucesso!";
    } catch (Exception $e) {
        if ($conn->errno == 1062) {
            $mensagem = "Ops! Este e-mail já está sendo usado. Tente outro.";
        } else {
            $mensagem = "Erro ao cadastrar: " . $conn->error;
        }
    }
}
?>

<html>
<head>
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Cadastrar Usuário</h1>
        <form method="POST">
            <?php if(!empty($mensagem)): ?>
                <?php 
                    $cor = (strpos($mensagem, 'Erro') !== false || strpos($mensagem, 'Ops') !== false) ? 'feedback-error' : 'feedback-success';
                ?>
                <div class="<?php echo $cor; ?>"><?php echo htmlspecialchars($mensagem); ?></div>
            <?php endif; ?>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required value="<?php echo isset($nome)?htmlspecialchars($nome):''; ?>"><br>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required value="<?php echo isset($email)?htmlspecialchars($email):''; ?>"><br>
            <button type="submit" name="submit">Cadastrar Usuário</button>
        </form>
    </div>
</body>
</html>
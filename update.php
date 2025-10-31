<?php
include 'db.php';

$usuario = null;
$mensagem = "";
$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (isset($_POST['submit']) && $id) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    try {
        $stmt = $pdo->prepare("UPDATE usuario SET nome = :nome, email = :email WHERE id = :id");
        $stmt->execute(['nome' => $nome, 'email' => $email, 'id' => $id]);
        $mensagem = "Usuário atualizado com sucesso!";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $mensagem = "Ops! Este e-mail já está sendo usado. Tente outro.";
        } else {
            $mensagem = "Erro ao atualizar: " . $e->getMessage();
        }
    }
}

if ($id) {
    $stmt_select = $pdo->prepare("SELECT * FROM usuario WHERE id = :id");
    $stmt_select->execute(['id' => $id]);
    $usuario = $stmt_select->fetch();
}

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit; 
}
?>
<html>
<head>
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Editar Usuário</h1>
    <?php if(!empty($mensagem)): ?>
        <?php 
            $cor = (strpos($mensagem, 'Erro') !== false || strpos($mensagem, 'Ops') !== false) ? 'feedback-error' : 'feedback-success';
        ?>
        <div class="<?php echo $cor; ?>"><?php echo htmlspecialchars($mensagem); ?></div>
    <?php endif; ?>
    <form method="POST" action="?id=<?= htmlspecialchars($usuario['id']) ?>">
        <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required><br>
        <button type="submit" name="submit">Atualizar Usuário</button>
        <a href="read.php" style="margin-left:20px;">Voltar</a>
    </form>
</div>
</body>
</html>

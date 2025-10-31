<?php
session_start(); 
include 'db.php'; 

if (isset($_GET['id'])) {
    $id_para_apagar = $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM usuario WHERE id = :id");
        $stmt->execute(['id' => $id_para_apagar]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['mensagem'] = "Usuário removido com sucesso!";
            $_SESSION['msg_tipo'] = "success";
        } else {
            $_SESSION['mensagem'] = "Usuário não encontrado ou já removido.";
            $_SESSION['msg_tipo'] = "warning";
        }
    } catch (PDOException $e) {
        $_SESSION['mensagem'] = "Ocorreu um erro ao tentar remover o usuário. Por favor, tente novamente.";
        $_SESSION['msg_tipo'] = "error";
    }
    header("Location: read.php");
    exit; 
}

$mensagem_feedback = "";
if (isset($_SESSION['mensagem'])) {
    $cor = ($_SESSION['msg_tipo'] === 'error') ? 'feedback-error' : (($_SESSION['msg_tipo'] === 'warning') ? 'feedback-warning' : 'feedback-success');
    $mensagem_feedback = "<div class='$cor'>" . $_SESSION['mensagem'] . "</div>";
    unset($_SESSION['mensagem']);
    unset($_SESSION['msg_tipo']);
}

// Lista de usuários para fallback visual (opcional)
try {
    $stmt_lista = $pdo->query("SELECT * FROM usuario ORDER BY nome");
    $usuarios = $stmt_lista->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $usuarios = [];
}
?>
<html>
<head>
    <title>Excluir Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Excluir Usuário</h1>
    <?php echo $mensagem_feedback; ?>
    <a href="read.php">Voltar para lista</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id'] ?></td>
                    <td><?= htmlspecialchars($usuario['nome']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php
include '../db.php';

$usuario = null;

$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (isset($_POST['submit']) && $id) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    
    $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email WHERE id_usuario = :id");
    $stmt->execute(['nome' => $nome, 'email' => $email, 'id' => $id]);

    echo "Usuário atualizado!";
   
}

if ($id) {
  
    $stmt_select = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
    $stmt_select->execute(['id' => $id]);
    $usuario = $stmt_select->fetch();
}

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit; 
}
?>

<form method="POST" action="?id=<?= htmlspecialchars($usuario['id_usuario']) ?>">
    
    <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">
    
    Nome: 
    <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required><br>
    
    Email: 
    <input type="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required><br>
    
    <button type="submit" name="submit">Atualizar Usuário</button>
</form>>
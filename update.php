<?php
include '../config/conexao.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $usuario = $pdo->query("SELECT * FROM usuarios WHERE id_usuario=$id")->fetch();
}

if(isset($_POST['submit'])){
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("UPDATE usuarios SET nome=:nome, email=:email WHERE id_usuario=:id");
    $stmt->execute(['nome'=>$nome,'email'=>$email,'id'=>$_POST['id']]);

    echo "Usuário atualizado!";
}
?>

<form method="POST">
    <input type="hidden" name="id" value="<?= $usuario['id_usuario'] ?>">
    Nome: <input type="text" name="nome" value="<?= $usuario['nome'] ?>" required><br>
    Email: <input type="email" name="email" value="<?= $usuario['email'] ?>" required><br>
    <button type="submit" name="submit">Atualizar Usuário</button>
</form>
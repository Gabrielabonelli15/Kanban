<?php
include '../config/conexao.php';

if(isset($_POST['submit'])){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nome'=>$nome, 'email'=>$email, 'senha'=>$senha]);

    echo "Usuário cadastrado!";
}
?>

<form method="POST">
    Nome: <input type="text" name="nome" required><br>
    Email: <input type="email" name="email" required><br>
    Senha: <input type="password" name="senha" required><br>
    <button type="submit" name="submit">Cadastrar Usuário</button>
</form>
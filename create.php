<?php
include 'config.php';

if(isset($_POST['submit'])){
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = 'Email inválido.';
    } else {
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        try {
            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nome'=>$nome, 'email'=>$email, 'senha'=>$senha]);
            $success = 'Usuário cadastrado!';
            $nome = '';
            $email = '';
        } catch (PDOException $e) {
            $error = 'Erro ao cadastrar usuário: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
    }
}
?>

<form method="POST">
    <?php if(!empty($error ?? false)): ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if(!empty($success ?? false)): ?>
        <div style="color: green;"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    Nome: <input type="text" name="nome" required value="<?php echo isset($nome)?htmlspecialchars($nome):''; ?>"><br>
    Email: <input type="email" name="email" required value="<?php echo isset($email)?htmlspecialchars($email):''; ?>"><br>
    Senha: <input type="password" name="senha" required><br>
    <button type="submit" name="submit">Cadastrar Usuário</button>
</form>

<?php
include '../config/conexao.php';

$usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Lista de Usuários</h2>
<table border="1">
<tr><th>ID</th><th>Nome</th><th>Email</th><th>Ações</th></tr>
<?php foreach($usuarios as $u): ?>
<tr>
    <td><?= $u['id_usuario'] ?></td>
    <td><?= $u['nome'] ?></td>
    <td><?= $u['email'] ?></td>
    <td>
        <a href="../Update/usuario.php?id=<?= $u['id_usuario'] ?>">Editar</a> |
        <a href="../Delete/usuario.php?id=<?= $u['id_usuario'] ?>" onclick="return confirm('Deletar?')">Deletar</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
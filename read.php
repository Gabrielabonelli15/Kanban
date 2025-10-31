<?php
include 'db.php'; 

$usuarios = []; 

try {
    $stmt = $pdo->query("SELECT * FROM usuario ORDER BY nome");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar usuários: " . $e->getMessage());
}
?>

<html>
<head>
    <title>Lista de Usuários</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Lista de Usuários</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($usuarios as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nome']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td>
                <a href="update.php?id=<?= $u['id'] ?>">Editar</a> |
                <a href="delete.php?id=<?= $u['id'] ?>" onclick="return confirm('Tem certeza que deseja apagar?')">Deletar</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (count($usuarios) === 0): ?>
        <tr>
            <td colspan="4">Nenhum usuário cadastrado.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</body>
</html>
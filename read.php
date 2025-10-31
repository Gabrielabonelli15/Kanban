<?php
include '../db.php'; 

$usuarios = []; 

try {

    $stmt = $pdo->query("SELECT * FROM usuarios ORDER BY nome");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao buscar usuários: " . $e->getMessage());
}
?>

<h2>Lista de Usuários</h2>
<table border="1">
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
            <td><?= $u['id_usuario'] ?></td>
            
            <td><?= htmlspecialchars($u['nome']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            
            <td>
                <a href="../Update/usuario.php?id=<?= $u['id_usuario'] ?>">Editar</a> |

                <form method="POST" action="../Delete/usuario.php" style="display:inline;">
                    <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                    
                    <button type="submit" 
                            onclick="return confirm('Tem certeza que deseja apagar?')"
                            style="background:none; border:none; color:blue; cursor:pointer; text-decoration:underline; padding:0;">
                        Deletar
                    </button>
                </form>
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
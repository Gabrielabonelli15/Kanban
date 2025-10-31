<?php
session_start(); 
include '../db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario_para_apagar'])) {
    $id_para_apagar = $_POST['id_usuario_para_apagar'];
    try {
        $stmt = $conn->prepare("DELETE FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $id_para_apagar);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $_SESSION['mensagem'] = "Usuário removido com sucesso!";
            $_SESSION['msg_tipo'] = "success";
        } else {
            $_SESSION['mensagem'] = "Usuário não encontrado ou já removido.";
            $_SESSION['msg_tipo'] = "warning";
        }
    } catch (Exception $e) {
        $_SESSION['mensagem'] = "Ocorreu um erro ao tentar remover o usuário. Por favor, tente novamente.";
        $_SESSION['msg_tipo'] = "error";
    }
    header("Location: delete.php");
    exit; 
}

$mensagem_feedback = "";
if (isset($_SESSION['mensagem'])) {
    $cor = ($_SESSION['msg_tipo'] === 'error') ? '#d9534f' : (($_SESSION['msg_tipo'] === 'warning') ? '#f0ad4e' : '#5cb85c');
    $mensagem_feedback = "<div style='background:$cor;color:white;padding:10px;border-radius:5px;margin-bottom:10px;font-weight:bold;'>" . $_SESSION['mensagem'] . "</div>";
    unset($_SESSION['mensagem']);
    unset($_SESSION['msg_tipo']);
}

try {
    $stmt_lista = $conn->prepare("SELECT * FROM usuario ORDER BY nome");
    $stmt_lista->execute();
    $result = $stmt_lista->get_result();
    $usuarios = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die("Erro ao buscar usuários: Por favor, tente novamente mais tarde.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; }
        table { border-collapse: collapse; width: 80%; margin: 30px auto; background: #fff; }
        th, td { padding: 10px 15px; border: 1px solid #ddd; text-align: center; }
        th { background: #007bff; color: #fff; }
        tr:nth-child(even) { background: #f2f2f2; }
        h1 { text-align: center; color: #333; }
        .btn-apagar { background: #d9534f; color: #fff; border: none; padding: 6px 14px; border-radius: 4px; cursor: pointer; }
        .btn-apagar:hover { background: #c9302c; }
    </style>
</head>
<body>
    <h1>Usuários Cadastrados</h1>
    <div style="width:80%;margin:0 auto;">
        <?php echo $mensagem_feedback; ?>
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
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="id_usuario_para_apagar" value="<?php echo $usuario['id']; ?>">
                                <button type="submit" class="btn-apagar" onclick="return confirm('Tem certeza que deseja remover este usuário?');">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($usuarios) === 0): ?>
                    <tr>
                        <td colspan="4">Nenhum usuário cadastrado no momento.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
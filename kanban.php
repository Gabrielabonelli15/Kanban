<?php include 'db.php'; ?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Gerenciamento de Tarefas</h2>
    <div class="menu">
        <a href="index.php">Menu Principal</a>
    </div>
    <?php
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $status = $_POST['status'];
        $prioridade = $_POST['prioridade'];
        if (in_array($status, ['a_fazer','fazendo','pronto']) && in_array($prioridade, ['baixa','media','alta'])) {
            $stmt = $conn->prepare("UPDATE tarefa SET status=?, prioridade=? WHERE id=?");
            $stmt->bind_param('ssi', $status, $prioridade, $id);
            $stmt->execute();
            $stmt->close();
        }
    }
    
        if (isset($_GET['excluir'])) {
            $id = intval($_GET['excluir']);
            $conn->query("DELETE FROM tarefa WHERE id=$id");
        }
        $tarefas = ['a_fazer'=>[], 'fazendo'=>[], 'pronto'=>[]];
        $result = $conn->query("SELECT t.*, u.nome AS usuario_nome FROM tarefa t JOIN usuario u ON t.id_usuario = u.id ORDER BY t.prioridade DESC, t.data_cadastro ASC");
        while ($row = $result->fetch_assoc()) {
            $tarefas[$row['status']][] = $row;
        }
        function prioridade_label($p) {
            return $p=='baixa'?'Baixa':($p=='media'?'Média':'Alta');
        }
        function status_label($s) {
            return $s=='a_fazer'?'A Fazer':($s=='fazendo'?'Fazendo':'Pronto');
        }
        ?>
    <div class="kanban">
        <?php foreach (['a_fazer','fazendo','pronto'] as $col) { ?>
        <div class="kanban-col">
            <h3><?= status_label($col) ?></h3>
            <?php foreach ($tarefas[$col] as $t) { ?>
            <div class="task">
                <strong><?= htmlspecialchars($t['descricao']) ?></strong><br>
                    Setor: <?= htmlspecialchars($t['setor']) ?><br>
                    Prioridade: <?= prioridade_label($t['prioridade']) ?><br>
                    Usuário: <?= htmlspecialchars($t['usuario_nome']) ?><br>
                    Status: <?= status_label($t['status']) ?><br>

                <div class="actions">
                    <form method="post" style="display:inline-block">
                        <input type="hidden" name="id" value="<?= $t['id'] ?>">
                        <select name="status">
                            <option value="a_fazer" <?= $t['status']=='a_fazer'?'selected':'' ?>>A Fazer</option>
                            <option value="fazendo" <?= $t['status']=='fazendo'?'selected':'' ?>>Fazendo</option>
                            <option value="pronto" <?= $t['status']=='pronto'?'selected':'' ?>>Pronto</option>
                        </select>

                        <select name="prioridade">
                            <option value="baixa" <?= $t['prioridade']=='baixa'?'selected':'' ?>>Baixa</option>
                            <option value="media" <?= $t['prioridade']=='media'?'selected':'' ?>>Média</option>
                            <option value="alta" <?= $t['prioridade']=='alta'?'selected':'' ?>>Alta</option>
                        </select>
                        <button type="submit">Atualizar</button>
                    </form>
                    <a href="tarefa_editar.php?id=<?= $t['id'] ?>">Editar</a> |
                    <a href="kanban.php?excluir=<?= $t['id'] ?>" onclick="return confirm('Confirma excluir esta tarefa?')">Excluir</a>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>
</body>
</html>
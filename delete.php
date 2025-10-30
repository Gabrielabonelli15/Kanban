<?php
include '../config/conexao.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario=?");
    $stmt->execute([$id]);
    header("Location: ../Read/usuario.php");
}
?>
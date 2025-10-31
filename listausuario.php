<?php include 'db.php'; ?>
<?php
$result = $conn->query("SELECT id, nome FROM usuario ORDER BY nome");
$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}
?>
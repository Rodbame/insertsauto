<?php
// Conecta a la base de datos
$pdo = new PDO('mysql:host=localhost;dbname=unmillon', 'root', '');

// Ejecuta la sentencia SQL
$stmt = $pdo->query('SELECT id, nombre , apellido , email FROM usuarios');

// Devuelve los resultados en formato JSON
echo json_encode(array('data' => $stmt->fetchAll(PDO::FETCH_ASSOC)));
?>

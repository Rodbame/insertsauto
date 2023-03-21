<?php

// Establecer la conexión con la base de datos
$dsn = 'mysql:host=localhost;dbname=unmillon;charset=utf8mb4';
$usuario = 'root';
$contraseña = '';

try {
    $conexion = new PDO($dsn, $usuario, $contraseña);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error al conectarse con la base de datos: ' . $e->getMessage();
    exit;
}
// Obtener el número total de registros en la tabla
$datos = $conexion->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();

// Mostrar los resultados en una tabla
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<th>ID</th>';
echo '<th>Nombre</th>';
echo '<th>Apellido</th>';
echo '<th>Correo</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
while ($registro = $datos->fetch()) {
    echo '<tr>';
    echo '<td>' . $registro['id'] . '</td>';
    echo '<td>' . $registro['nombre'] . '</td>';
    echo '<td>' . $registro['apellido'] . '</td>';
    echo '<td>' . $registro['correo'] . '</td>';
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';

?>
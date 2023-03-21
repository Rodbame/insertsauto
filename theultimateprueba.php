<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'unmillon';
$username = 'root';
$password = '';

// Conexión a la base de datos utilizando PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error al conectarse a la base de datos: " . $e->getMessage();
    exit;
}

// Definición de la cantidad de elementos por página
$elementos_por_pagina = 10;

// Determinación de la página actual
$pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;

// Determinación del offset de la consulta
$offset = ($pagina_actual - 1) * $elementos_por_pagina;

// Búsqueda por correo electrónico y nombre de usuario
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

// Preparación de la consulta SQL con paginación y búsqueda
$sql = "SELECT * FROM usuarios";
$sql_count = "SELECT COUNT(*) FROM usuarios";
$params = array();
if (!empty($busqueda)) {
    $sql .= " WHERE correo LIKE :busqueda OR nombre LIKE :busqueda";
    $params['busqueda'] = '%' . $busqueda . '%';
}
// Recuperación de los resultados
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtención del número total de elementos
$stmt_count->execute($params);
$total_elementos = $stmt_count->fetchColumn();

// Cálculo del número total de páginas
$total_paginas = ceil($total_elementos / $elementos_por_pagina);

// Cálculo del rango de páginas a mostrar
$rango_paginas = 5;
$primer_pagina = max(1, $pagina_actual - $rango_paginas);
$ultima_pagina = min($total_paginas, $pagina_actual + $rango_paginas);

// Creación de la tabla HTML para mostrar los resultados
echo '<table>';
echo '<tr><th>Nombre de usuario</th><th>Correo electrónico</th></tr>';
foreach ($resultados as $fila) {
echo '<tr>';
echo '<td>' . htmlspecialchars($fila['nombre_de_usuario']) . '</td>';
echo '<td>' . htmlspecialchars($fila['correo_electronico']) . '</td>';
echo '</tr>';
}
echo '</table>';

// Creación de la barra de paginación y búsqueda
echo '<div>';
echo '<form>';
echo '<label for="busqueda">Buscar:</label>';
echo '<input type="text" name="busqueda" value="' . htmlspecialchars($busqueda) . '">';
echo '<input type="submit" value="Buscar">';
echo '</form>';
echo '<form>';
echo '<label for="pagina">Ir a la página:</label>';
echo '<input type="number" name="pagina" min="1" max="' . $total_paginas . '" value="' . $pagina_actual . '">';
echo '<input type="submit" value="Ir">';
echo '</form>';
echo '<ul>';
if ($pagina_actual > 1) {
echo '<li><a href="?pagina=1&busqueda=' . urlencode($busqueda) . '">Primera</a></li>';
echo '<li><a href="?pagina=' . ($pagina_actual - 1) . '&busqueda=' . urlencode($busqueda) . '">Anterior</a></li>';
}
for ($i = $primer_pagina; $i <= $ultima_pagina; $i++) {
echo '<li';
if ($i == $pagina_actual) {
echo ' class="activo"';
}
echo '><a href="?pagina=' . $i . '&busqueda=' . urlencode($busqueda) . '">' . $i . '</a></li>';
}
if ($pagina_actual < $total_paginas) {
echo '<li><a href="?pagina=' . ($pagina_actual + 1) . '&busqueda=' . urlencode($busqueda) . '">Siguiente</a></li>';
echo '<li><a href="?pagina=' . $total_paginas . '&busqueda=' . urlencode($busqueda) . '">Última</a></li>';
}
echo '</ul>';
echo '</div>';
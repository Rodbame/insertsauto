<?php
// Conexión a la base de datos
$dsn = 'mysql:host=localhost;dbname=unmillon;charset=utf8mb4';
$usuario = 'root';
$contrasena = '';

try {
    $conexion = new PDO($dsn, $usuario, $contrasena);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error al conectarse a la base de datos: ' . $e->getMessage();
    exit();
}

// Parámetros de la paginación
$por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;

// Filtro de búsqueda
$filtro = '';
if (isset($_GET['buscar'])) {
    $filtro = trim($_GET['buscar']);
}

$consulta_total = "SELECT COUNT(*) AS total FROM usuarios nombre LIKE :filtro";


// Consulta SQL con la paginación y el filtro
$inicio = ($pagina_actual - 1) * $por_pagina;
$consulta = "SELECT * FROM usuarios WHERE correo LIKE :filtro OR nombre LIKE :filtro LIMIT $inicio, $por_pagina";
$statement = $conexion->prepare($consulta);
$statement->bindValue(':filtro', "$filtro%", PDO::PARAM_STR);
$statement->execute();
$usuarios = $statement->fetchAll(PDO::FETCH_ASSOC);

// Consulta SQL para contar el total de resultados
$consulta_total = "SELECT COUNT(*) AS total FROM usuarios WHERE correo LIKE :filtro OR nombre LIKE :filtro";
$statement = $conexion->prepare($consulta_total);
$statement->bindValue(':filtro', "$filtro%", PDO::PARAM_STR);
$statement->execute();
$total = $statement->fetch(PDO::FETCH_ASSOC)['total'];

// Cálculo de las páginas y el enlace a la siguiente página
$total_paginas = ceil($total / $por_pagina);
$siguiente_pagina = $pagina_actual + 1;

// Formulario de búsqueda

echo '<h1 class="display-4 text-center mt-5">Busqueda en un millon de datos</h1>';
echo '<div class="container">';
echo '<form method="get">';
echo '<input type="text" name="buscar" value="' . htmlspecialchars($filtro) . '" class="form-control" placeholder="Buscar por nombre y correo...">';
echo '<button type="submit" class="btn btn-secondary">Buscar</button>';
echo '</form>';

// Tabla de resultados
echo '<div class="card m-">';
echo '<table class="table table-hover">';
echo '<tr class=""><th>ID</th><th>Nombre</th><th>Apellido</th><th>Correo</th></tr>';

foreach ($usuarios as $usuario) {
    echo '<tr">';
    echo '<td>' . $usuario['id'] . '</td>';
    echo '<td>' . $usuario['nombre'] . '</td>';
    echo '<td>' . $usuario['apellido'] . '</td>';
    echo '<td>' . $usuario['correo'] . '</td>';
    echo '</tr>';
}

echo '</table>';
echo '</div>';

// Paginación
echo '<div class="m-4">';
if ($pagina_actual > 1) {
    echo '<a class="a__anterior" href="?pagina=' . ($pagina_actual - 1) . '&buscar=' . urlencode($filtro) . '">Anterior</a>';
}
echo ' Página <span class="negritas">' . $pagina_actual . '</span> de ' . $total_paginas . ' ';
if ($pagina_actual < $total_paginas) {
    echo '<a class="a__siguiente" href="?pagina=' . $siguiente_pagina . '&buscar=' . urlencode($filtro) . '">Siguiente</a>';
}
echo '</div>';


echo '</div>';

?>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="./bootstrap.min.css">
  <title>Muestra de los resultados</title>
</head>
<body>

</body>
</html>


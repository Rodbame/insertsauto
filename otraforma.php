<?php
// Establecer la conexión con la base de datos
$dsn = 'mysql:host=localhost;dbname=unmillon;charset=utf8mb4';
$usuario = 'root';
$contraseña = '';
$opciones = [
    PDO::ATTR_EMULATE_PREPARES => false, // Deshabilitar emulación de consultas preparadas para mejorar seguridad y rendimiento
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Habilitar excepciones en errores de MySQL
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Establecer modo de obtención de resultados por defecto a asociativo
    PDO::MYSQL_ATTR_FOUND_ROWS => true, // Habilitar opción para obtener número de filas afectadas en UPDATE y DELETE
];
$conexion = new PDO($dsn, $usuario, $contraseña, $opciones);

// Definir la cantidad de resultados por página
$resultados_por_pagina = 20;

// Obtener el número total de registros en la tabla
$sql_total = "SELECT COUNT(*) AS total FROM usuarios";
$stmt_total = $conexion->query($sql_total);
$total_registros = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];

// Calcular el número total de páginas
$total_paginas = ceil($total_registros / $resultados_por_pagina);

// Obtener el número de página actual desde la variable GET
$pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
if ($pagina_actual < 1) {
    $pagina_actual = 1;
} elseif ($pagina_actual > $total_paginas) {
    $pagina_actual = $total_paginas;
}

// Calcular el índice del primer resultado en la página actual
$indice_primer_resultado = ($pagina_actual - 1) * $resultados_por_pagina;

// Obtener los términos de búsqueda desde la variable GET
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

// Obtener los registros de la tabla usuarios que corresponden a la página actual y la búsqueda
if (!empty($busqueda)) {
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM usuarios WHERE nombre LIKE :busqueda OR correo LIKE :busqueda LIMIT :inicio, :cantidad";
    $stmt = $conexion->prepare($sql);
    $stmt->bindValue(':busqueda', "%{$busqueda}%");
} else {
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM usuarios LIMIT :inicio, :cantidad";
    $stmt = $conexion->prepare($sql);
}
$stmt->bindValue(':inicio', $indice_primer_resultado, PDO::PARAM_INT);
$stmt->bindValue(':cantidad', $resultados_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar los registros en una tabla HTML
echo '<table id="myTable">';
echo '<thead><tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Correo</th></tr></thead>';
echo '<tbody>';
foreach ($registros as $registro) {
    echo '<tr><td>' . htmlspecialchars($registro['id']).'</td><td>'.htmlspecialchars($registro['nombre']) .'</td><td>'.htmlspecialchars($registro['apellido']). '</td><td>' . htmlspecialchars($registro['correo']) . '</td></tr>';
}
echo '</tbody>';
echo '</table>';

// Generar la barra de paginación
echo '<div>';
if ($total_paginas > 1) {
    echo '<span>Página ' . $pagina_actual . ' de ' . $total_paginas . '</span>';

    // Mostrar enlaces a las páginas anteriores
    if ($pagina_actual > 1) {
        echo '<a href="?pagina=' . ($pagina_actual - 1) . '&busqueda=' . urlencode($busqueda) . '">Anterior</a>';
    }

    // Mostrar enlaces a las páginas siguientes
    if ($pagina_actual < $total_paginas) {
        echo '<a href="?pagina=' . ($pagina_actual + 1) . '&busqueda=' . urlencode($busqueda) . '">Siguiente</a>';
    }

    // Mostrar los números de página
    $inicio_paginacion = max(1, $pagina_actual - 5);
    $fin_paginacion = min($total_paginas, $pagina_actual + 5);
    for ($i = $inicio_paginacion; $i <= $fin_paginacion; $i++) {
        if ($i === $pagina_actual) {
            echo '<span>' . $i . '</span>';
        } else {
            echo '<a href="?pagina=' . $i . '&busqueda=' . urlencode($busqueda) . '">' . $i . '</a>';
        }
    }

}
echo '</div>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap -->
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />
    <head>
	<title>Tabla de usuarios</title>
	<style type="text/css">
		table {
			border-collapse: collapse;
			width: 100%;
		}

		th, td {
			text-align: left;
			padding: 8px;
			border: 1px solid #ddd;
		}

		tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		.active {
			background-color: #4CAF50;
			color: white;
		}

		.pagination {
			display: inline-block;
			margin: 10px 0;
		}

		.pagination a {
			color: black;
			float: left;
			padding: 8px 16px;
			text-decoration: none;
		}

		.pagination a.active {
			background-color: #4CAF50;
            color: white;
	    }

        .pagination a:hover:not(.active) {
		background-color: #ddd;
	}
</style>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.13/datatables.min.css"/>
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.13/datatables.min.js"></script>
</head>
<body>

// Mostrar la barra de búsqueda
    <form method="get">
    <input type="text" name="busqueda" value="' . htmlspecialchars($busqueda) . '">
    <button type="submit">Buscar</button>
    </form>
    
</body>
</html>


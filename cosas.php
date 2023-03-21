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

// Definir la cantidad de resultados por página
$resultados_por_pagina = 15;

// Obtener el número total de registros en la tabla
$total_registros = $conexion->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();

// Calcular el número total de páginas
$num_paginas = ceil($total_registros / $resultados_por_pagina);

// Obtener el número de página actual
$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Calcular el índice del primer resultado en la página actual
$primer_resultado = ($pagina_actual - 1) * $resultados_por_pagina;

// Consultar los registros de la tabla usuarios
$consulta = $conexion->prepare('SELECT * FROM usuarios LIMIT :primer_resultado, :resultados_por_pagina');
$consulta->bindParam(':primer_resultado', $primer_resultado, PDO::PARAM_INT);
$consulta->bindParam(':resultados_por_pagina', $resultados_por_pagina, PDO::PARAM_INT);
$consulta->execute();


// Mostrar los resultados en una tabla
echo '<div>';
echo '<table id="mytable" class="table">';
echo '<thead>';
echo '<tr>';
echo '<th>ID</th>';
echo '<th>Nombre</th>';
echo '<th>Apellido</th>';
echo '<th>Correo</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
while ($registro = $consulta->fetch()) {
    echo '<tr>';
    echo '<td>' . $registro['id'] . '</td>';
    echo '<td>' . $registro['nombre'] . '</td>';
    echo '<td>' . $registro['apellido'] . '</td>';
    echo '<td>' . $registro['correo'] . '</td>';
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';

// Mostrar la barra de paginación
$enlaces_mostrados = 10;
$primera_pagina = max(1, $pagina_actual - $enlaces_mostrados);
$ultima_pagina = min($num_paginas, $pagina_actual + $enlaces_mostrados);

echo '<div class="pagination">';
if ($pagina_actual > 1) {
    echo '<a href="?pagina=' . ($pagina_actual - 1) . '">&laquo; Anterior</a>';
} else {
    echo '<span class="disabled">&laquo; Anterior</span>';
}

for ($i = $primera_pagina; $i <= $ultima_pagina; $i++) {
    if ($i == $pagina_actual) {
        echo '<a href="#" class="active">' . $i . '</a>';
    } else {
        echo '<a href="?pagina=' . $i . '">' . $i . '</a>';
    }
}

if ($pagina_actual < $num_paginas) {
    echo '<a href="?pagina=' . ($pagina_actual + 1) . '">Siguiente &raquo;</a>';
} else {
    echo '<span class="disabled">Siguiente</span>';
}
echo '</div>';

// Cerrar la conexión
$conexion = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busqueda a un millon</title>
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
<link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>

    
    <!-- Agrega un formulario de búsqueda -->
    <form method="post">
        <input type="text" name="txtbuscar" placeholder="Buscar...">
        <button type="submit">Buscar</button>
    </form>
    
<script>
    $(document).ready(function() {
      $('#myTable').DataTable({
        "ajax": "get_data.php",
        "columns": [
          {"data": "id"},
          {"data": "nombre"},
          {"data": "apellidos"},
          {"data": "correo"}
        ]
      });
    });
</script>
</body>
</html>


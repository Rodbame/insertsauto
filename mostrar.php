<?php
// Establecer los parámetros de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unmillon";

// Parámetros para paginar los resultados
$por_pagina = 100; // número de registros por página
$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // página actual

// Crear la conexión utilizando PDO
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

// Obtener el número total de registros
try {
    $stmt = $conn->query("SELECT COUNT(*) FROM usuarios");
    $num_registros = $stmt->fetchColumn();
} catch(PDOException $e) {
    echo "Error al obtener el número de registros: " . $e->getMessage();
}


// Calcular el número total de páginas
$num_paginas = ceil($num_registros / $por_pagina);

// Calcular el índice inicial y final de los registros a mostrar
$indice_inicio = ($pagina_actual - 1) * $por_pagina;
$indice_final = $indice_inicio + $por_pagina - 1;


// Realizar la consulta select a la base de datos
try {
    // Preparar la consulta
    $stmt = $conn->prepare("SELECT id, nombre, apellido, correo FROM usuarios ORDER BY id LIMIT :inicio, :cantidad");
    // Asignar valores a las variables :inicio y :cantidad
    $stmt->bindParam(':inicio', $indice_inicio, PDO::PARAM_INT);
    $stmt->bindParam(':cantidad', $por_pagina, PDO::PARAM_INT);
    // Ejecutar la consulta
    $stmt->execute();
    // Obtener todos los resultados en un array
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error al realizar la consulta: " . $e->getMessage();
}
// Cerrar la conexión
$conn = null;
?>

<!DOCTYPE html>
<html>
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
</head>
<body>
<h1>Tabla de usuarios</h1>

<table>
	<tr>
		<th>ID</th>
		<th>Nombre</th>
		<th>Apellido</th>
		<th>Correo</th>
	</tr>
	<?php foreach ($resultados as $row) { ?>
		<tr>
			<td><?php echo $row['id']; ?></td>
			<td><?php echo $row['nombre']; ?></td>
			<td><?php echo $row['apellido']; ?></td>
			<td><?php echo $row['correo']; ?></td>
		</tr>
	<?php } ?>
</table>

<div class="pagination">
	<?php if ($pagina_actual > 1) { ?>
		<a href="?pagina=<?php echo $pagina_actual - 1; ?>">&laquo; Anterior</a>
	<?php } else { ?>
		<span class="disabled">&laquo; Anterior</span>
	<?php } ?>

	<?php for ($i = 1; $i <= $num_paginas; $i++) { ?>
		<?php if ($i == $pagina_actual) { ?>
			<a href="#" class="active"><?php echo $i; ?></a>
		<?php } else { ?>
			<a href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
		<?php } ?>
	<?php } ?>

	<?php if ($pagina_actual < $num_paginas) { ?>
		<a href="?pagina=<?php echo $pagina_actual + 1; ?>">Siguiente &raquo;</a>
	<?php } else { ?>
		<span class="disabled">Siguiente &raquo;</span>
	<?php } ?>
</div>

</body>
</html>

<?php

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unmillon";

// Conexión a la base de datos usando PDO
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Habilitar excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}

// Obtener la página actual para la paginación
if(isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

// Establecer el número de resultados por página
$results_per_page = 20;

// Si hay términos de búsqueda, realizar la consulta
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    // Utilizar una consulta preparada con índices para optimizar la búsqueda
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre LIKE :search LIMIT :start, :results_per_page");
    $stmt->bindValue(':search', '%'.$search.'%', PDO::PARAM_STR);
    $stmt->bindValue(':start', ($page - 1) * $results_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':results_per_page', $results_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($results);
} else {
    // Si no hay términos de búsqueda, obtener todos los registros y mostrarlos paginados
    $stmt = $conn->prepare("SELECT * FROM usuarios");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($results);
    $start = ($page - 1) * $results_per_page;
    $results = array_slice($results, $start, $results_per_page);
}

// Calcular el número total de páginas
$total_pages = ceil($count / $results_per_page);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Búsqueda en base de datos con paginación</title>
</head>
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
<body>
    <h1>Búsqueda en base de datos con paginación</h1>
    <form action="" method="GET">
        <input type="text" name="search">
        <button type="submit">Buscar</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Correo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($results as $row) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['apellido']; ?></td>
                <td><?php echo $row['correo']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php if(!isset($_GET['search'])) { ?>
    <div class="pagination">
        <?php for($i = 1; $i <= $total_pages; $i++) { ?>
        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</body>
</html>
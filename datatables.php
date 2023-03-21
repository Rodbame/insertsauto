<?php

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unmillon";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}

// Paginación
$results_per_page = 30;
$page_nums_per_set = 10;

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$limit_start = ($page - 1) * $results_per_page;

// Búsqueda
if (isset($_GET['search'])) {
    $search = $_GET['search'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre LIKE :search LIMIT :start, :limit");
    $stmt->bindValue(':search', '%'.$search.'%');
    $stmt->bindValue(':start', $limit_start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $results_per_page, PDO::PARAM_INT);
    $stmt->execute();

    $total_results = $stmt->rowCount();
} else {
    $stmt = $conn->prepare("SELECT COUNT(*) AS nombre FROM usuarios");
    $stmt->execute();

    $total_results = $stmt->fetch()['nombre'];

    $stmt = $conn->prepare("SELECT * FROM usuarios LIMIT :start, :limit");
    $stmt->bindValue(':start', $limit_start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $results_per_page, PDO::PARAM_INT);
    $stmt->execute();
}

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cálculo de la paginación
$total_pages = ceil($total_results / $results_per_page);
$current_set = ceil($page / $page_nums_per_set);
$total_sets = ceil($total_pages / $page_nums_per_set);
$set_start = (($current_set - 1) * $page_nums_per_set) + 1;
$set_end = ($set_start + $page_nums_per_set) - 1;
if ($set_end > $total_pages) {
    $set_end = $total_pages;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Barra de búsqueda y paginación</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        tr:nth-child(even){background-color: #f2f2f2}
        th {
            background-color: #4CAF50;
            color: white;
        }
        .pagination {
            display: inline-block;
        }
        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }
        .pagination a:hover:not(.active) {background-color: #ddd;}
        .prev, .next {
            border: none;
            background-color: transparent;
            color: black
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
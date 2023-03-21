<?php
// Establecer los parámetros de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unosmiles";

// Crear la conexión utilizando PDO
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}
 


// Desactivar los índices de la tabla temporalmente
$conn->exec("ALTER TABLE usuarios DISABLE KEYS");

// Iniciar el contador de tiempo
$start_time = microtime(true);

$nombres = array(
    "Juan",
    "María",
    "Lucas",
    "Sofía",
    "Mateo",
    "Valentina",
    "Santiago",
    "Martina",
    "Tomás",
    "Mía",
    "Agustín",
    "Camila",
    "Nicolás",
    "Florencia",
    "Ignacio",
    "Renata",
    "Joaquín",
    "Abril",
    "Facundo",
    "Delfina",
    "Francisco",
    "Candela",
    "Emiliano",
    "Victoria",
    "Lautaro",
    "Julieta",
    "Gonzalo",
    "Agustina",
    "Luciano",
    "Luna",
    "Maximiliano",
    "Catalina",
    "Ezequiel",
    "Emilia",
    "Javier",
    "Bianca",
    "Mariano",
    "Juliana",
    "Alejandro",
    "Paula",
    "Ramiro",
    "Josefina",
    "Leonardo",
    "Micaela",
    "Benjamín",
    "Pilar",
    "Gustavo",
    "Daniela",
    "Leandro",
    "Milagros",
    "Rodrigo",
    "Brenda",
    "Mauricio",
    "Carla",
    "Hernán",
    "Virginia",
    "Manuel",
    "Jazmín",
    "Nahuel",
    "Ailín",
    "Carlos",
    "Selena",
    "Diego",
    "Natalia",
    "Facundo",
    "Julián",
    "Luz",
    "Evelyn",
    "Germán",
    "Antonella",
    "Matías",
    "Florencia",
    "Federico",
    "Anabella",
    "Juan Manuel",
    "Ayelén",
    "Alan",
    "Sol",
    "Sebastián",
    "Romina",
    "Marcos",
    "Melina",
    "Lucas",
    "Melanie",
    "Hugo",
    "Romina",
    "Pablo",
    "Florencia",
    "Nicolás",
    "Aixa",
    "Claudio",
    "Adriana",
    "Emmanuel",
    "Lucía",
    "Esteban",
    "Luciana",
    "Mauro",
    "Luciana",
    "Bruno",
    "Mariana",
    "Iván",
    "Camila",
    "Giselle",
    "Solange",
    "Matías",
    "Ana",
    "Lionel",
    "Lucila",
    "Gabriel",
    "Ana Paula",
    "Jorge",
    "Micaela",
    "Roberto",
    "Natalia",
    "Gonzalo",
    "Carolina",
    "Facundo",
    "Bárbara",
    "Ezequiel",
    "Luciana",
    "Ariel",
    "Jimena",
    "Rubén",
    "Gabriela",
    "Martín",
    "Carolina",
    "Gustavo",
    "Valeria",
    "Francisco",
    "Melisa",
    "Walter",
    "Luciana",
    "Enzo",
    "Belén",
    "Juan Pablo",
    "María José",
    "Nicolás",
    "Julieta",
    "Agustín",
    "Vanesa",
    "José",
    "Carina",
    "Luis",
    "Marina"
);

$apellidos = array(
    "Acosta",
    "Aguirre",
    "Alaniz",
    "Albornoz",
    "Alcaraz",
    "Alonso",
    "Alvarez",
    "Amaya",
    "Andrade",
    "Arce",
    "Arias",
    "Arroyo",
    "Avila",
    "Ayala",
    "Baez",
    "Barrera",
    "Barrios",
    "Benitez",
    "Blanco",
    "Bonilla",
    "Bravo",
    "Britos",
    "Cabrera",
    "Campos",
    "Cardozo",
    "Carmona",
    "Carranza",
    "Carrizo",
    "Castro",
    "Centurion",
    "Cervantes",
    "Chávez",
    "Contreras",
    "Correa",
    "Cortés",
    "Costa",
    "Cruz",
    "Cuellar",
    "Córdoba",
    "Díaz",
    "Domínguez",
    "Durán",
    "Escobar",
    "Espinosa",
    "Estrada",
    "Farias",
    "Fernández",
    "Flores",
    "Franco",
    "Gallegos",
    "García",
    "Garza",
    "Gómez",
    "González",
    "Guerrero",
    "Gutiérrez",
    "Hernández",
    "Herrera",
    "Ibarra",
    "Iglesias",
    "Jiménez",
    "Juarez",
    "Lara",
    "Leiva",
    "León",
    "Linares",
    "López",
    "Lucero",
    "Luna",
    "Machado",
    "Maldonado",
    "Manrique",
    "Martínez",
    "Medina",
    "Mendoza",
    "Mercado",
    "Molina",
    "Montenegro",
    "Morales",
    "Moreno",
    "Muñoz",
    "Navarro",
    "Núñez",
    "Ojeda",
    "Ortiz",
    "Pacheco",
    "Padilla",
    "Palacios",
    "Pérez",
    "Ponce",
    "Quiroga",
    "Ramírez",
    "Ríos",
    "Rivera",
    "Robles",
    "Rodríguez",
    "Rojas",
    "Romero",
    "Rosas",
    "Ruiz"
);

// Generar los datos a insertar (en este caso, números aleatorios del 1 al 1000)
$sql = "INSERT INTO usuarios (nombre, apellido,correo) VALUES (:nombre, :apellido, :correo)";
$stmt = $conn->prepare($sql);
for ($i = 1; $i <= 5000; $i++) {
    $numero_aleatorio = rand(0,99);
    $numero_aleatorio2 = rand(0,99);
    $token3 = $numero_aleatorio.bin2hex(openssl_random_pseudo_bytes(4));
    $correo=$nombres[$numero_aleatorio].".".$apellidos[$numero_aleatorio2].$token3."@gmail.com";
    // echo $correo;

    $nombreinsert=$nombres[$numero_aleatorio];
    $apellidosinsert=$apellidos[$numero_aleatorio2];
    $stmt->bindParam(':nombre', $nombreinsert);
    $stmt->bindParam(':apellido', $apellidosinsert);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
}

// Reactivar los índices de la tabla
$conn->exec("ALTER TABLE usuarios ENABLE KEYS");



// Calcular el tiempo total de ejecución
$end_time = microtime(true);
$total_time = $end_time - $start_time;

// Mostrar el tiempo total de ejecución
echo "Tiempo total de ejecución: " . round($total_time, 2) . " segundos";

// Cerrar la conexión
$conn = null;

?> 
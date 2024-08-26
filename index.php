<?php
$servername = "localhost";
$username = "root";
$password = "Mycr8760ne14.";
$dbname = "instituto_andres_bello";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $rut = $_POST['rut'];
    $direccion = $_POST['direccion'];
    $carrera = $_POST['carrera'];
    $fecha_periodo = $_POST['fecha_periodo'];

    $sql = "INSERT INTO estudiantes (nombre, apellido, rut, direccion, carrera, fecha_periodo)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $nombre, $apellido, $rut, $direccion, $carrera, $fecha_periodo);

    try {
        $stmt->execute();
        $message = "<p class='message success'>¡Registro creado exitosamente!</p>";
    } catch (mysqli_sql_exception $e) {
        $message = "<p class='message error'>Error al crear el registro: " . $e->getMessage() . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro Estudiantil</title>
    <link rel="stylesheet" href="./styles/index.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Registro Estudiantil</a></li>
                <li><a href="carreras.php">Información de Carreras</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="container">
        <h1>Registro Estudiantil</h1>
        <?= $message; ?>
        <form action="index.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
            
            <label for="rut">RUT:</label>
            <input type="text" id="rut" name="rut" required>
            
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>
            
            <label for="carrera">Carrera a Cursar:</label>
            <select id="carrera" name="carrera" required>
                <option value="">Seleccione una carrera</option>
                <option value="Ingeniería Civil">Ingeniería Civil</option>
                <option value="Medicina">Medicina</option>
                <option value="Derecho">Derecho</option>
                <option value="Arquitectura">Arquitectura</option>
                <!-- Agrega más opciones según sea necesario -->
            </select>
            
            <label for="fecha_periodo">Fecha del Período Académico:</label>
            <input type="date" id="fecha_periodo" name="fecha_periodo" required>
            
            <button type="submit">Registrar</button>
        </form>
    </div>
</body>
</html>

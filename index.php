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
    $nombre = htmlspecialchars(trim($_POST['nombre']), ENT_QUOTES, 'UTF-8');
    $apellido = htmlspecialchars(trim($_POST['apellido']), ENT_QUOTES, 'UTF-8');
    $rut = htmlspecialchars(trim($_POST['rut']), ENT_QUOTES, 'UTF-8');
    $direccion = htmlspecialchars(trim($_POST['direccion']), ENT_QUOTES, 'UTF-8');
    $carrera = htmlspecialchars(trim($_POST['carrera']), ENT_QUOTES, 'UTF-8');
    $fecha_periodo = $_POST['fecha_periodo'];

    if (!preg_match("/^[A-Za-z\s]{2,50}$/", $nombre)) {
        $message = "<p class='message error'>Nombre inválido. Solo se permiten entre 2 y 50 caracteres, debe ser letras y espacios.</p>";
    } elseif (!preg_match("/^[A-Za-z\s]{2,50}$/", $apellido)) {
        $message = "<p class='message error'>Apellido inválido. Solo se permiten entre 2 y 50 caracteres, debe ser letras y espacios</p>";
    } elseif (!preg_match("/^\d{1,2}\.\d{3}\.\d{3}-[\dkK]{1}$/", $rut)) {
        $message = "<p class='message error'>Formato de RUT inválido. Debe ser en el formato 12.345.678-9.</p>";
    } elseif (strlen($direccion) < 5 || strlen($direccion) > 100) {
        $message = "<p class='message error'>Dirección debe tener entre 5 y 100 caracteres.</p>";
    } elseif (empty($carrera)) {
        $message = "<p class='message error'>Debe seleccionar una carrera.</p>";
    } elseif (empty($fecha_periodo)) {
        $message = "<p class='message error'>Debe seleccionar una fecha para el período académico.</p>";
    } else {
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
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Estudiantil</title>
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
            <input type="text" id="nombre" name="nombre" required pattern="[A-Za-z\s]{2,50}" title="Nombre debe contener entre 2 y 50 caracteres, solo letras y espacios">

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required pattern="[A-Za-z\s]{2,50}" title="Apellido debe contener entre 2 y 50 caracteres, solo letras y espacios">

            <label for="rut">RUT:</label>
            <input type="text" id="rut" name="rut" required pattern="\d{1,2}\.\d{3}\.\d{3}-[\dkK]{1}" title="Formato RUT válido: 12.345.678-9">>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required pattern=".{5,100}" title="Dirección debe tener entre 5 y 100 caracteres">

            <label for="carrera">Carrera a Cursar:</label>
            <select id="carrera" name="carrera" required>
                <option value="">Seleccione una carrera</option>
                <option value="Ingeniería Civil">Ingeniería Civil</option>
                <option value="Medicina">Medicina</option>
                <option value="Derecho">Derecho</option>
                <option value="Arquitectura">Arquitectura</option>
            </select>

            <label for="fecha_periodo">Fecha del Período Académico:</label>
            <input type="date" id="fecha_periodo" name="fecha_periodo" required>

            <button type="submit">Registrar</button>
        </form>
    </div>
</body>

</html>
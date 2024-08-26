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

$sql = "SELECT nombre, descripcion FROM carreras";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carreras</title>
    <link rel="stylesheet" href="./styles/carreras.css">
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
        <h1>Información de Carreras</h1>

        <?php
        if ($result->num_rows > 0) {
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li><strong>" . $row["nombre"] . "</strong> " . $row["descripcion"] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No hay carreras disponibles en este momento.</p>";
        }
        $conn->close();
        ?>
    </div>
</body>

</html>
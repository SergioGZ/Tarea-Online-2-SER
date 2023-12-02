<?php
session_start();
require_once 'config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Verificar si se ha proporcionado un ID válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idEntrada = $_GET['id']; 

    // Obtener los datos de la entrada con el ID proporcionado
    try {
        $queryObtenerEntrada = "SELECT * FROM entradas WHERE ID = :id";
        $stmt = $conexion->prepare($queryObtenerEntrada);
        $stmt->bindParam(':id', $idEntrada);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $entrada = $stmt->fetch(PDO::FETCH_ASSOC);
            $titulo = $entrada['titulo'];
            $categoria_id = $entrada['categoria_id'];
            $descripcion = $entrada['descripcion'];

            //Se prohibe el acceso si no es el mismo usuario que la creó y no es administrador
            if ($_SESSION['id'] !== $entrada['usuario_id']  && $_SESSION['rol'] != 1) {
                header('Location: index.php');
                exit;
            }

        } else {
            echo "Entrada no encontrada.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error al obtener la entrada: " . $e->getMessage();
        exit;
    }
} else {
    echo "ID de entrada no válido.";
    exit;
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Borrar Entrada</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-secondary">

    <?php include 'includes/header.html';?>

        <div class="row">
            <div class="col-12 p-5 m-3">
                <?php
                    // Mostrar confirmación de borrado
                    echo "<h2>Confirmar Borrado</h2>";
                    echo "<p>¿Estás seguro de que deseas borrar la entrada con ID {$idEntrada}?</p>";
                    echo "<a href='eliminar.php?id={$idEntrada}' class='btn btn-danger'>Sí, Borrar</a> ";
                    echo "<a href='index.php' class='btn btn-secondary'>Cancelar</a>";
                ?>
            </div>
            <div class="col-12">
                <a class="ms-3 ps-5" href="index.php">Volver</a>
            </div>
        </div>
    </div>

</body>

</html>
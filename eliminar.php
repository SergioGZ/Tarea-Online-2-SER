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

    try {
        // Realizar la acción de borrado en la base de datos
        $queryBorrarEntrada = "DELETE FROM entradas WHERE ID = :id";
        $stmt = $conexion->prepare($queryBorrarEntrada);
        $stmt->bindParam(':id', $idEntrada);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error al borrar la entrada: " . $e->getMessage();
    }
} else {
    echo "ID de entrada no válido.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Entrada</title>
    <!-- Agrega el enlace al archivo CSS de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-secondary">

    <?php include 'includes/header.html';?>

        <div class="row">
            <div class="col-12 p-5 m-5">
                <h2>Entrada eliminada</h2>
            </div>
            <div class="col-12">
                <a class="ms-5 ps-5" href="index.php">Volver</a>
            </div>
        </div>
    </div>

</body>
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
        $queryObtenerUsuario = "SELECT * FROM usuarios WHERE ID = :id";
        $stmt = $conexion->prepare($queryObtenerUsuario);
        $stmt->bindParam(':id', $idEntrada);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            //Se prohibe el acceso si no es el mismo usuario que la creó y no es administrador
            if ($_SESSION['id'] !== $usuario['id']  && $_SESSION['rol'] != 1) {
                header('Location: index.php');
                exit;
            }

        } else {
            echo "Usuario no encontrada.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error al obtener el usuario: " . $e->getMessage();
        exit;
    }
} else {
    echo "ID de usuario no válido.";
    exit;
}

try {
    // Realizar la acción de borrado en la base de datos
    $queryBorrarUsuario = "DELETE FROM usuarios WHERE ID = :id";
    $stmt = $conexion->prepare($queryBorrarUsuario);
    $stmt->bindParam(':id', $idEntrada);
    $stmt->execute();
} catch (PDOException $e) {
    echo "Error al borrar el usuario: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar usuario</title>
    <!-- Agrega el enlace al archivo CSS de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-secondary">

    <?php include 'includes/header.html';?>

        <div class="row">
            <div class="col-12 p-5 m-5">
                <h2>Usuario eliminado</h2>
            </div>
            <div class="col-12">
                <a class="ms-5 ps-5" href="index.php">Volver</a>
            </div>
        </div>
    </div>

</body>
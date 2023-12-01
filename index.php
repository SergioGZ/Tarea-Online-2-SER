<?php
session_start();
require_once 'config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Resto del código de la página
try {
    // Consulta SQL con INNER JOIN para obtener datos de entradas, categorías y usuarios
    $query = "
        SELECT entradas.ID, entradas.titulo, entradas.descripcion, categorias.nombre AS categoria, usuarios.nick AS usuario_nick, entradas.fecha, entradas.imagen
        FROM entradas
        INNER JOIN categorias ON entradas.categoria_id = categorias.id
        INNER JOIN usuarios ON entradas.usuario_id = usuarios.id
        ORDER BY entradas.fecha DESC
    ";
    $stmt = $conexion->query($query);
} catch (PDOException $e) {
    echo "Error al realizar la consulta: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
</head>

<body class="bg-secondary">

    <?php include 'includes/header.html';?>

        <div class="row">
            <div class="col-12 w-50">
                <ul class="list-group p-4 pt-lg-5 float-start">
                    <li class="list-group-item text-center"><a class="btn btn-primary" href="crearentrada.php">Añadir entrada</a></li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?php
                    // Mostrar la tabla HTML con los resultados de la consulta
                    echo "<h1>Entradas</h1>";
                    echo "<table class='table table-striped col-12' style=''>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Imagen</th>
                        <th>Operaciones</th>
                    </tr>";

                    while ($entrada = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                        <td>{$entrada['ID']}</td>
                        <td>{$entrada['titulo']}</td>
                        <td>{$entrada['descripcion']}</td>
                        <td>{$entrada['categoria']}</td>
                        <td>{$entrada['usuario_nick']}</td>
                        <td>{$entrada['fecha']}</td>
                        <td><img src='{$entrada['imagen']}' alt='Sin imagen' style='max-width: 50px; max-height: 50px;'></td>
                        <td>
                            <a href='listar.php?id={$entrada['ID']}' class='btn btn-primary'><i class='bi bi-eye-fill'></i></a> 
                            <a href='modificar.php?id={$entrada['ID']}' class='btn btn-primary'><i class='bi bi-pencil-square'></i></a> 
                            <a href='borrar.php?id={$entrada['ID']}' class='btn btn-danger'><i class='bi bi-trash'></i></a>
                        </td>
                        </tr>";
                    }

                    echo "</table>";
                ?>

            </div>
        </div>
    </div>

</body>

</html>
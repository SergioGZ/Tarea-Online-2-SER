<?php
session_start();
require_once 'config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

try {
    // Consulta SQL con INNER JOIN para obtener datos de entradas, categorías y usuarios
    $query = "
        SELECT entradas.ID, entradas.usuario_id, entradas.titulo, entradas.descripcion, categorias.nombre AS categoria, usuarios.nick AS usuario_nick, entradas.fecha, entradas.imagen
        FROM entradas
        INNER JOIN categorias ON entradas.categoria_id = categorias.id
        INNER JOIN usuarios ON entradas.usuario_id = usuarios.id
    ";
    $stmtEntradas = $conexion->query($query);
    $stmtEntradas->execute();
} catch (PDOException $e) {
    echo "Error al realizar la consulta de la tabla entradas: " . $e->getMessage();
}

try {
    // Consulta SQL para tabla usuarios
    $query = "
        SELECT *
        FROM usuarios
    ";
    $stmtUsuarios = $conexion->query($query);
    $stmtUsuarios->execute();
} catch (PDOException $e) {
    echo "Error al realizar la consulta de la tabla usuarios: " . $e->getMessage();
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
    <link rel="stylesheet" href="http://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <?php include 'includes/header.html'; ?>

    <div class="row">
        <div class="col-12 w-50 my-5">
            <a class="btn btn-primary float-start text-center me-3" href="crearentrada.php">Añadir entrada</a>
            <a class="btn btn-primary float-start text-center" href="crearusuario.php">Añadir usuario</a>
        </div>
    </div>

    <div class="row bg-light">
        <div class="col-12">
            <?php
            // Mostrar la tabla entradas
            echo
            "<h1>Entradas</h1>
            <table id='tabla' class='table table-responsive table-striped datatable col-12'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Imagen</th>
                        <th>Operaciones</th>
                    </tr>
                </thead>

                <tbody>";
            while ($entrada = $stmtEntradas->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$entrada['ID']}</td>
                        <td>{$entrada['titulo']}</td>
                        <td style='max-width: 200px;'>{$entrada['descripcion']}</td>
                        <td>{$entrada['categoria']}</td>
                        <td>{$entrada['usuario_nick']}</td>
                        <td>{$entrada['fecha']}</td>
                        <td><img src='{$entrada['imagen']}' alt='Sin imagen' style='max-width: 50px; max-height: 50px;'></td>
                        <td>
                            <a href='listar.php?id={$entrada['ID']}' class='btn btn-primary'><i class='bi bi-eye-fill'></i></a> ";

                            if ($_SESSION['id'] == $entrada['usuario_id'] || $_SESSION['rol'] == 1) {
                                echo "<a href='modificar.php?id={$entrada['ID']}' class='btn btn-primary'><i class='bi bi-pencil-square'></i></a>
                                        <a href='borrar.php?id={$entrada['ID']}' class='btn btn-danger'><i class='bi bi-trash'></i></a>";
                            } else {
                                echo "<a href='modificar.php?id={$entrada['ID']}' class='btn btn-primary disabled'><i class='bi bi-pencil-square'></i></a>
                                        <a href='borrar.php?id={$entrada['ID']}' class='btn btn-danger disabled'><i class='bi bi-trash'></i></a>";
                            }
                echo "</td>
                        </tr>";
            }
            
            echo "</tbody>
                </table>";
            ?>
        </div>
    </div>

    <div class="row bg-light mt-5">
        <div class="col-12">
            <?php
            // Mostrar la tabla usuarios
            echo
            "<h1>Usuarios</h1>
            <table id='tabla2' class='table table-responsive table-striped datatable col-12'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nick</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Contraseña</th>
                        <th>Rol</th>
                        <th>Avatar</th>
                        <th>Operaciones</th>
                    </tr>
                </thead>

                <tbody>";
            while ($usuario = $stmtUsuarios->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$usuario['id']}</td>
                        <td>{$usuario['nick']}</td>
                        <td>{$usuario['nombre']}</td>
                        <td>{$usuario['apellidos']}</td>
                        <td>{$usuario['email']}</td>
                        <td>{$usuario['password']}</td>
                        <td>{$usuario['rol']}</td>
                        <td><img src='{$usuario['imagen_avatar']}' alt='Sin avatar' style='max-width: 50px; max-height: 50px;'></td>
                        <td>";
                            if ($_SESSION['id'] == $usuario['id'] || $_SESSION['rol'] == 1) {
                                echo "<a href='modificarUsuario.php?id={$usuario['id']}' class='btn btn-primary'><i class='bi bi-pencil-square'></i></a>
                                        <a href='borrar.php?id={$usuario['id']}' class='btn btn-danger'><i class='bi bi-trash'></i></a>";
                            } else {
                                echo "<a href='modificarUsuario.php?id={$usuario['id']}' class='btn btn-primary disabled'><i class='bi bi-pencil-square'></i></a>
                                        <a href='borrar.php?id={$usuario['id']}' class='btn btn-danger disabled'><i class='bi bi-trash'></i></a>";
                            }
                echo "</td>
                        </tr>";
            }

            echo "</tbody>
                </table>";
            ?>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="http://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        var table = new DataTable('#tabla', {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
        });
        var table2 = new DataTable('#tabla2', {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
        });
    </script>
</body>

</html>
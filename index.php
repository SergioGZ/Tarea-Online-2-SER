<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Resto del código de la página 
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
</head>

<body class="bg-secondary">

    <div class="container bg-light" style="height: 100vh;">
        <div class="row bg-black" style="height:95px;">
            <div class="col-6">
                <h2 class="text-light m-4 pt-1">Bienvenido, <?php echo $_SESSION['nombre']; ?>!</h2>
            </div>
            <div class="col-6">
                <a class="float-end m-4 pt-1" href="logout.php">Cerrar sesión</a>
            </div>
        </div>

        <div class="row">
            <div class="col-6 w-50">
                <ul class="list-group p-4 pt-lg-5 float-end">
                    <li class="list-group-item text-center"><a class="btn btn-primary">Añadir entrada</a></li>
                    <li class="list-group-item text-center"><a class="btn btn-primary">Listar entradas</a></li>
                </ul>
            </div>
            <div class="col-6">
                <img class="img-fluid pt-5 pt-lg-3 w-50" src="monitor.png" />
            </div>
        </div>
    </div>

</body>

</html>
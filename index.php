<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Resto del código de la página principal
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

<body>
    <div class="container-fluid bg-secondary">
        <div class="container">
            <div class="row bg-black p-4" style="height:10vh;">
                <div class="col-6">
                    <h2 class="text-light">Bienvenido, <?php echo $_SESSION['username']; ?>!</h2>
                </div>
                <div class="col-6">
                    <a class="float-end mt-lg-2" href="logout.php">Cerrar sesión</a>
                </div>
            </div>
            <div class="row bg-light" style="height:90vh;">
                <div class="col-6">
                    dasdsad
                </div>
                <div class="col-6">
                    
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php
session_start();
require_once 'config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Procesar el formulario si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');

    // Puedes hacer validaciones adicionales aquí antes de insertar en la base de datos

    try {
        // Insertar nueva entrada en la tabla "usuarios"
        $queryInsertarCategoria = "INSERT INTO categorias (nombre) VALUES (:nombre)";
        $stmt = $conexion->prepare($queryInsertarCategoria);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();

        $confirmacion = '<div class="alert alert-success w-25 text-center">Categoría creada correctamente</div>';
    } catch (PDOException $e) {
        echo "Error al crear la entrada: " . $e->getMessage() . "<br>" . var_dump($nombre);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear categoría</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-secondary">

    <?php include 'includes/header.html'; ?>

    <div class="row bg-light">
        <div class="col-12">
            <form method="POST" action="" enctype="multipart/form-data" class="p-5 m-5 needs-validation" novalidate>
                <label class="form-label" for="nombre"><strong>Nombre:</strong></label>
                <input class="form-control w-50" type="text" name="nombre" required pattern="[A-Za-z]{1,15}">
                <div class="invalid-feedback">Solo se permiten letras. Máximo de 15 caracteres</div><br/>

                <input class="btn btn-primary" type="submit" value="Crear categoría">

                <?php
                if (!empty($confirmacion)) {
                    echo "<p>$confirmacion</p>";
                }
                ?>
            </form>
            <a class="ms-5 ps-5" href="index.php">Volver</a>
        </div>
    </div>
    </div>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>

</html>
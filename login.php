<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nick = $_POST['nick'];
    $password = $_POST['password'];

    try {
        $query = "SELECT * FROM usuarios WHERE nick=:nick AND password=:password";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':nick', $nick);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            // Inicio de sesión exitoso
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['loggedin'] = true;
            $_SESSION['nombre'] = $user['nombre'];  // Cambiado a 'nombre'
            $_SESSION['nick'] = $nick;
            header('Location: index.php'); // Redirige a la página principal
            exit;
        } else {
            $error = '<div class="alert alert-danger w-50 text-center m-auto">Nombre de usuario o contraseña incorrectos</div>';
        }
    } catch (PDOException $e) {
        die("Error al ejecutar la consulta: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <?php if (isset($error)) {
            echo "<p>$error</p>";
        } ?>

        <div class="row mt-3">
            <div class="col-12">
                <h2 class="mt-3">Iniciar sesión</h2>
            </div>
        </div>

        <form method="post" action="login.php" class="row g-3 mt-3 border border-1 p-3 needs-validation" novalidate>
            <div class="col-6">
                <label for="nick" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="nick" name="nick" placeholder="Usuario" required>
                <div class="invalid-feedback">
                    Campo requerido
                </div>
            </div>

            <div class="col-6">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                <div class="invalid-feedback">
                    Campo requerido
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            </div>
        </form>

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
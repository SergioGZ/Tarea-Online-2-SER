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
    $nick = htmlspecialchars($_POST['nick'], ENT_QUOTES, 'UTF-8');
    $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
    $apellidos = htmlspecialchars($_POST['apellidos'], ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $rol = filter_var($_POST['rol'], FILTER_VALIDATE_INT);
    $avatar = ''; // Inicializar con un valor predeterminado
    if ($_FILES['avatar']['error'] == 0) {
        $avatar = 'avatares/' . uniqid() . '_' . htmlspecialchars(basename($_FILES['avatar']['name']), ENT_QUOTES, 'UTF-8');
        move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar);
    }

    try {
        // Insertar nueva entrada en la tabla "usuarios"
        $queryInsertarUsuario = "
            INSERT INTO usuarios (nick, nombre, apellidos, email, password, rol, imagen_avatar)
            VALUES (:nick, :nombre, :apellidos, :email, :password, :rol, :imagen_avatar)
        ";
        $stmt = $conexion->prepare($queryInsertarUsuario);
        $stmt->bindParam(':nick', $nick);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':imagen_avatar', $avatar);
        $stmt->execute();

        $confirmacion = '<div class="alert alert-success w-25 text-center">Usuario creado correctamente</div>';
    } catch (PDOException $e) {
        echo "Error al crear la entrada: " . $e->getMessage() . "<br>" . var_dump($nick, $nombre, $apellidos, $email, $password, $rol, $avatar);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-secondary">

    <?php include 'includes/header.html'; ?>

    <div class="row bg-light">
        <div class="col-12">
            <form method="POST" action="crearusuario.php" enctype="multipart/form-data" class="p-5 m-5 needs-validation" novalidate>
                <label class="form-label" for="nick"><strong>Nick:</strong></label>
                <input class="form-control w-50" type="text" name="nick" required pattern="[A-Za-z0-9_]{1,10}">
                <div class="invalid-feedback">Longitud máxima de 10 caracteres. Alfanuméricos y guiones bajos</div><br/>

                <label class="form-label" for="nombre"><strong>Nombre:</strong></label>
                <input class="form-control w-50" type="text" name="nombre" required pattern="[A-Za-z\s]{1,20}">
                <div class="invalid-feedback">Longitud máxima de 20. Solo letras y espacios</div><br/>

                <label class="form-label" for="apellidos"><strong>Apellidos:</strong></label>
                <input class="form-control w-50" type="text" name="apellidos" required pattern="[A-Za-z\s]{1,40}">
                <div class="invalid-feedback">Longitud máxima de 40. Solo letras y espacios</div><br/>

                <label class="form-label" for="email"><strong>Email:</strong></label>
                <input class="form-control w-50" type="email" name="email" required><br>
                
                <label class="form-label" for="password"><strong>Contraseña:</strong></label>
                <input class="form-control w-50" type="password" name="password" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$">
                <div class="invalid-feedback">Debe contener al menos: 8 caracteres, una minúscula, una mayúscula, un dígito y un caracter especial [@$!%*?&]</div><br/>

                <label class="form-label" for="rol"><strong>Rol:</strong></label>
                <select class="form-select w-50" name="rol" required>
                    <option value="0">0</option>
                    <option value="1">1</option>
                </select><br>

                <label class="form-label" for="avatar"><strong>Avatar:</strong></label>
                <input class="form-control" type="file" name="avatar" accept="image/*"><br>

                <input class="btn btn-primary" type="submit" value="Crear Usuario">
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
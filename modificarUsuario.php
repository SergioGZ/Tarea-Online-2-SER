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
        $queryObtener = "SELECT * FROM usuarios WHERE ID = :id";
        $stmt = $conexion->prepare($queryObtener);
        $stmt->bindParam(':id', $idEntrada);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            $nick = $usuario['nick'];
            $nombre = $usuario['nombre'];
            $apellidos = $usuario['apellidos'];
            $email = $usuario['email'];
            $password = $usuario['password'];
            $rol = $usuario['rol'];
            $avatar = $usuario['imagen_avatar'];

            //Se prohibe el acceso si no es el mismo usuario que la creó y no es administrador
            if ($_SESSION['id'] !== $usuario['id']  && $_SESSION['rol'] != 1) {
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


// Verificar si el formulario ha sido enviado mediante el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nick = $_POST['nick'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    $avatar = '';
    if ($_FILES['avatar']['error'] == 0) {
        $avatar = 'avatares/' . uniqid() . '_' . htmlspecialchars(basename($_FILES['avatar']['name']), ENT_QUOTES, 'UTF-8');
        move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar);
    }

    // Puedes hacer validaciones adicionales aquí antes de insertar en la base de datos

    try {
        // Actualizar los campos de la entrada en la base de datos
        $queryActualizar = "UPDATE usuarios SET nick = :nick, nombre = :nombre, apellidos = :apellidos, email = :email, password = :password, rol = :rol, imagen_avatar = :avatar WHERE ID = :id";
        $stmt = $conexion->prepare($queryActualizar);
        $stmt->bindParam(':id', $idEntrada);
        $stmt->bindParam(':nick', $nick);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':avatar', $avatar);
        $stmt->execute();

        $confirmacion = '<div class="alert alert-success w-75 text-center">Entrada modificada correctamente</div>';
    } catch (PDOException $e) {
        echo "Error al actualizar la entrada: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Modificar usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-secondary">

    <?php include 'includes/header.html'; ?>

    <div class="row bg-light">
        <div class="col-12">
            <form method="POST" action="" enctype="multipart/form-data" class="p-5 m-5 needs-validation" novalidate>
                <label class="form-label" for="nick"><strong>Nick:</strong></label>
                <input class="form-control w-50" type="text" name="nick" value="<?php echo $nick; ?>" required><br>

                <label class="form-label" for="nombre"><strong>Nombre:</strong></label>
                <input class="form-control w-50" type="text" name="nombre" value="<?php echo $nombre; ?>" required><br>

                <label class="form-label" for="apellidos"><strong>Apellidos:</strong></label>
                <input class="form-control w-50" type="text" name="apellidos" value="<?php echo $apellidos; ?>" required><br>

                <label class="form-label" for="email"><strong>Email:</strong></label>
                <input class="form-control w-50" type="email" name="email" value="<?php echo $email; ?>" required><br>

                <label class="form-label" for="password"><strong>Contraseña:</strong></label>
                <input class="form-control w-50" type="password" name="password" value="<?php echo $password; ?>" required><br>

                <label class="form-label" for="rol"><strong>Rol:</strong></label>
                <select class="form-select w-50" name="rol" required>
                    <option value="0">0</option>
                    <option value="1">1</option>
                </select><br>           

                <label class="form-label" for="avatar"><strong>Avatar:</strong></label>
                <input class="form-control" type="file" name="avatar" accept="image/*">
                <p class="form-text">Necesario subir avatar de nuevo</p><br>

                <input class="btn btn-primary" type="submit" value="Modificar Entrada">
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
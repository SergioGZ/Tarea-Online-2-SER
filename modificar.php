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
        $queryObtenerEntrada = "SELECT * FROM entradas WHERE ID = :id";
        $stmt = $conexion->prepare($queryObtenerEntrada);
        $stmt->bindParam(':id', $idEntrada);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $entrada = $stmt->fetch(PDO::FETCH_ASSOC);
            $titulo = $entrada['titulo'];
            $categoria_id = $entrada['categoria_id'];
            $descripcion = $entrada['descripcion'];

            //Se prohibe el acceso si no es el mismo usuario que la creó y no es administrador
            if ($_SESSION['id'] !== $entrada['usuario_id']  && $_SESSION['rol'] != 1) {
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
    $titulo = $_POST['titulo'];
    $categoria_id = $_POST['categoria'];
    $descripcion = $_POST['descripcion'];

    $imagen = ''; // Inicializar con un valor predeterminado
    if ($_FILES['imagen']['error'] == 0) {
        $imagen = 'imagenes/' . uniqid() . '_' . htmlspecialchars(basename($_FILES['imagen']['name']), ENT_QUOTES, 'UTF-8');
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
    }

    // Puedes hacer validaciones adicionales aquí antes de insertar en la base de datos

    try {
        // Actualizar los campos de la entrada en la base de datos
        $queryActualizarEntrada = "UPDATE entradas SET titulo = :titulo, categoria_id = :categoria, descripcion = :descripcion, imagen = :imagen WHERE ID = :id";
        $stmt = $conexion->prepare($queryActualizarEntrada);
        $stmt->bindParam(':id', $idEntrada);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':categoria', $categoria_id);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':imagen', $imagen);
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
    <title>Crear Entrada</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-secondary">

    <?php include 'includes/header.html'; ?>

    <div class="row bg-light">
        <div class="col-12">
            <form method="POST" action="" enctype="multipart/form-data" class="p-5 m-5 needs-validation" novalidate>
                <label class="form-label" for="titulo"><strong>Título:</strong></label>
                <input class="form-control w-50" type="text" name="titulo" value="<?php echo $titulo; ?>" required><br>

                <label class="form-label" for="categoria"><strong>Categoría:</strong></label>
                <select class="form-select w-50" name="categoria" required>
                    <!-- Aquí puedes obtener las categorías de la base de datos y generar opciones -->
                    <?php
                    $queryCategorias = "SELECT * FROM categorias";
                    $stmtCategorias = $conexion->query($queryCategorias);
                    while ($categoria = $stmtCategorias->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($categoria['id'] == $categoria_id) ? 'selected' : '';
                        echo "<option value='{$categoria['id']}' {$selected}>" . htmlspecialchars($categoria['nombre'], ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                    ?>
                </select><br>

                <label class="form-label" for="descripcion"><strong>Descripción:</strong></label><br>
                <textarea class="form-control" name="descripcion" rows="4" required><?php echo $descripcion; ?></textarea><br>

                <label class="form-label" for="imagen"><strong>Imagen:</strong></label>
                <input class="form-control" type="file" name="imagen" accept="image/*">
                <p class="form-text">Necesario subir imagen de nuevo</p><br>

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
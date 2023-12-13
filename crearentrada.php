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
    $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES, 'UTF-8');
    $categoria_id = filter_var($_POST['categoria'], FILTER_VALIDATE_INT);
    $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8');
    $fecha = date("Y-m-d"); // Obtener la fecha actual

    $imagen = ''; // Inicializar con un valor predeterminado
    if ($_FILES['imagen']['error'] == 0) {
        $imagen = 'imagenes/' . uniqid() . '_' . htmlspecialchars(basename($_FILES['imagen']['name']), ENT_QUOTES, 'UTF-8');
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
    }

    // Puedes hacer validaciones adicionales aquí antes de insertar en la base de datos

    try {
        // Insertar nueva entrada en la tabla "entradas"
        $queryInsertarEntrada = "
            INSERT INTO entradas (usuario_id, categoria_id, titulo, descripcion, fecha, imagen)
            VALUES (:usuario_id, :categoria_id, :titulo, :descripcion, :fecha, :imagen)
        ";
        $stmt = $conexion->prepare($queryInsertarEntrada);
        $stmt->bindParam(':usuario_id', $_SESSION['id']);
        $stmt->bindParam(':categoria_id', $categoria_id);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':imagen', $imagen);
        $stmt->execute();

        $confirmacion = '<div class="alert alert-success w-25 text-center"> Entrada creada correctamente</div>';
    } catch (PDOException $e) {
        echo "Error al crear la entrada: " . $e->getMessage();
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

    <?php include 'includes/header.html';?>

        <div class="row bg-light">
            <div class="col-12">
                <form method="POST" action="crearentrada.php" enctype="multipart/form-data" class="p-5 m-5 needs-validation" novalidate>
                    <label class="form-label" for="titulo"><strong>Título:</strong></label>
                    <input class="form-control w-50" type="text" name="titulo" required pattern="[a-zA-Z0-9\s]{1,30}">
                    <div class="invalid-feedback">Solo caracteres alfanuméricos. Longitud máxima de 30</div><br/>

                    <label class="form-label" for="categoria"><strong>Categoría:</strong></label>
                    <select class="form-select w-50" name="categoria" required>
                        <!-- Aquí puedes obtener las categorías de la base de datos y generar opciones -->
                        <?php
                        $queryCategorias = "SELECT * FROM categorias";
                        $stmtCategorias = $conexion->query($queryCategorias);
                        while ($categoria = $stmtCategorias->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$categoria['id']}'>{$categoria['nombre']}</option>";
                        }
                        ?>
                    </select><br>

                    <label class="form-label" for="descripcion"><strong>Descripción:</strong></label><br>
                    <textarea class="form-control" name="descripcion" rows="4" required maxlength="75"></textarea>
                    <div class="invalid-feedback">Longitud máxima de 75</div><br/>

                    <label class="form-label" for="imagen"><strong>Imagen:</strong></label>
                    <input class="form-control" type="file" name="imagen" accept="image/*"><br>

                    <input class="btn btn-primary" type="submit" value="Crear Entrada">
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
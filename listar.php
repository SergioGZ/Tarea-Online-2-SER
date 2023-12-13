<?php
session_start();
require_once 'config.php';
require 'TCPDF/tcpdf.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Verificar si se ha proporcionado un ID válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idEntrada = $_GET['id'];

    $query = "
        SELECT 
            entradas.ID,
            entradas.titulo,
            entradas.descripcion,
            categorias.nombre AS categoria,
            usuarios.nick AS nick,
            entradas.fecha,
            entradas.imagen
        FROM entradas
        INNER JOIN categorias ON entradas.categoria_id = categorias.id
        INNER JOIN usuarios ON entradas.usuario_id = usuarios.id
        WHERE entradas.ID = :id
    ";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':id', $idEntrada);
    $stmt->execute();

    // Verificar si se encontró la entrada
    if ($stmt->rowCount() === 1) {
        // Obtener los datos de la entrada
        $entrada = $stmt->fetch(PDO::FETCH_ASSOC);

        // Aquí puedes mostrar los datos de la entrada
        $id = $entrada['ID'];
        $titulo = $entrada['titulo'];
        $categoria = $entrada['categoria'];
        $descripcion = $entrada['descripcion'];
        $nick = $entrada['nick'];
        $fecha = $entrada['fecha'];
        // Mostrar la imagen si está definida
        if (!empty($entrada['imagen'])) {
            $imagen =  $entrada['imagen'];
        } else {
            $imagen =  "No hay imagen disponible.";
        }
    } else {
        echo "No se encontró la entrada.";
    }
} else {
    echo "ID de entrada no proporcionado.";
}

function generarPDF($id, $nick, $titulo, $descripcion, $imagen, $fecha)
{
    // Crear una instancia de TCPDF
    $pdf = new TCPDF();

    // Establecer metadatos del documento
    $pdf->SetCreator($id);
    $pdf->SetAuthor($nick);
    $pdf->SetTitle($titulo);

    // Agregar una página al PDF
    $pdf->AddPage();

    // Agregar texto al PDF
    $pdf->SetFont('times', '', 12);
    $pdf->Cell(0, 10, $fecha, 0, 1);
    $pdf->Cell(0, 10, $nick, 0, 1);
    $pdf->Cell(0, 10, $titulo, 0, 1);
    $pdf->Cell(0, 10, $descripcion, 0, 1);

    // Agregar una imagen al PDF
    $imagePath = $imagen; // Reemplaza con la ruta de tu imagen
    $pdf->Image($imagePath, 10, 60, 80, 60, 'JPEG'); // Parámetros: URL/ruta, x, y, ancho, alto, formato

    // Guardar el PDF en el servidor o mostrarlo en el navegador
    $pdf->Output('entrada.pdf', 'I');
}

// Verificar si se ha enviado la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Llamar a la función para generar el PDF cuando se reciba la solicitud
    generarPDF($id, $nick, $titulo, $descripcion, $imagen, $fecha);
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
    <?php include 'includes/header.html'; ?>
    <div class="row">
        <div class="col-12">
            <div class="container mt-3">
                <h2 class="d-flex justify-content-start">Entrada #<?php echo $id; ?></h2>
                <h1 class="d-flex justify-content-center"><?php echo $titulo; ?></h1>
                <img class="w-50 d-flex mx-auto" src="<?php echo $imagen; ?>" alt="Entrada" />
                <div class="descipcion d-flex justify-content-center mx-auto mt-3 w-75">
                    <p class="text-justify"><?php echo $descripcion; ?></p>
                </div>
            </div>
            <form action="" method="post">
                <button class="btn btn-primary ms-5 mt-5" type="submit">Generar PDF</button>
            </form>
        </div>
        <a class="ms-5 ps-5 mt-4" href="index.php">Volver</a>
    </div>
    </div>

</body>
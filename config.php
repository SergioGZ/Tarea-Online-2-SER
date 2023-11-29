<?php
    // Conexión a la base de datos usando PDO
    $host = "localhost";
    $usuario = "root";
    $clave = "";
    $bd = "bdBlog";

    try{
        $conexion = new PDO("mysql:host=$host;dbname=$bd",$usuario,$clave);
        $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        echo '<div class="alert alert-success w-50 text-center mx-auto mt-3">' . "Conectado correctamente a la base de datos" . '</div>';
    } catch(PDOException$ex) {
        echo '<div class="alert alert-danger">' . "Error al conectar con la base de datos <br/>" . $ex->getMessage() . '</div>';
    }
?>
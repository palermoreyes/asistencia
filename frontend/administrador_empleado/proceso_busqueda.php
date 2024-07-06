<?php
require '../../backend/bd/ctconex.php';  

$titulo = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_SPECIAL_CHARS);

// SQL para seleccionar los registros con coincidencia exacta
$resultado_titulo = "SELECT dniem FROM empleado WHERE dniem = :titulo ORDER BY dniem ASC LIMIT 1";

// Preparar y ejecutar la consulta
$resultado_contenido = $connect->prepare($resultado_titulo);
$resultado_contenido->bindParam(':titulo', $titulo);
$resultado_contenido->execute();

// Inicializar el array $data
$data = [];

while($registros = $resultado_contenido->fetch(PDO::FETCH_ASSOC)){
    $data[] = $registros['dniem'];
}

echo json_encode($data);
?>

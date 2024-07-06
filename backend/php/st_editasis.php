<?php
require '../../backend/bd/ctconex.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtener datos del formulario
        $idasem = filter_input(INPUT_POST, 'txtidasem', FILTER_SANITIZE_NUMBER_INT);
        $ingreso = filter_input(INPUT_POST, 'txtIngreso', FILTER_SANITIZE_STRING);
        $inbreak = filter_input(INPUT_POST, 'txtInBreak', FILTER_SANITIZE_STRING);
        $fnbreak = filter_input(INPUT_POST, 'txtFinBreak', FILTER_SANITIZE_STRING);
        $salida = filter_input(INPUT_POST, 'txtSalida', FILTER_SANITIZE_STRING);
        $estado = filter_input(INPUT_POST, 'txtEstado', FILTER_SANITIZE_STRING);

        // Validar datos según tus necesidades
        // Aquí puedes agregar más lógica para validar los datos

        // Preparar la consulta SQL
        $sql = "UPDATE asis_empl SET ingreso = :ingreso, inbreak = :inbreak, fnbreak = :fnbreak, salida = :salida, estado = :estado WHERE idasem = :idasem";
        $stmt = $connect->prepare($sql);

        // Vincular parámetros
        $stmt->bindParam(':ingreso', $ingreso);
        $stmt->bindParam(':inbreak', $inbreak);
        $stmt->bindParam(':fnbreak', $fnbreak);
        $stmt->bindParam(':salida', $salida);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':idasem', $idasem);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Éxito al actualizar el registro
            echo json_encode(['success' => true, 'message' => 'Cambios guardados correctamente']);
        } else {
            // Error al actualizar el registro
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el registro']);
        }
    } catch (PDOException $e) {
        // Error en la base de datos
        echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    // Acceso no permitido
    echo json_encode(['success' => false, 'message' => 'Acceso no permitido']);
}
?>

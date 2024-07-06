<?php

if(isset($_POST['stupdempl'])) {
    $idemp = $_POST['txtidemp'];
    $dniem = $_POST['txtnume'];
    $nomem = $_POST['txtnome'];
    $apeem = $_POST['txtapell'];
    $programa = $_POST['txtprog'];
    $coord = $_POST['txtcoord'];
    $idcar = $_POST['txtcar'];
    $estado = $_POST['txtest'];

    // Determinar el valor de baja basado en el estado
    $baja = $estado === "Inactivo" ? "Inactivo por adm" : NULL;

    try {
        // Incluir la columna baja en el UPDATE si estado es Inactivo
        $query = "UPDATE empleado SET dniem=:dniem, nomem=:nomem, apeem=:apeem, idcar=:idcar, programa=:programa, coord=:coord, estado=:estado, baja=:baja WHERE idemp=:idemp LIMIT 1";
        $statement = $connect->prepare($query);

        $data = [
            ':dniem' => $dniem,
            ':nomem' => $nomem,
            ':apeem' => $apeem,
            ':idcar' => $idcar,
            ':programa' => $programa,
            ':coord' => $coord,
            ':estado' => $estado,
            ':baja' => $baja, // Agregar baja al arreglo de datos
            ':idemp' => $idemp
        ];
        $query_execute = $statement->execute($data);

        if($query_execute) {
            echo '<script type="text/javascript">
swal("¡Actualizado!", "Actualizado correctamente", "success").then(function() {
            window.location = "../empleado/mostrar.php";
        });
        </script>';
            exit(0);
        } else {
            echo '<script type="text/javascript">
swal("Error!", "Error al actualizar", "error").then(function() {
            window.location = "../empleado/mostrar.php";
        });
        </script>';
            exit(0);
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>
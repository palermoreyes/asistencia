<?php
if (isset($_POST['st_disabasis'])) {
    require '../../backend/bd/ctconex.php';

    $idasem = $_POST['txtidasem'];

    try {
        $query = "UPDATE asis_empl SET estado='Inactivo' WHERE idasem=:idasem LIMIT 1";
        $statement = $connect->prepare($query);

        $data = [
            ':idasem' => $idasem
        ];

        $query_execute = $statement->execute($data);

        if ($query_execute) {
            echo '<script type="text/javascript">
                    alert("Actualizado correctamente");
                    window.location = "../../frontend/reporte/asistencia.php";
                  </script>';
            exit(0);
        } else {
            echo '<script type="text/javascript">
                    alert("Error al actualizar");
                    window.location = "../../frontend/reporte/asistencia.php";
                  </script>';
            exit(0);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>

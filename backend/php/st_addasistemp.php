<?php
if(isset($_POST['staddasem'])){
///////////// Informacion enviada por el formulario /////////////
    $idemp=trim($_POST['txtemp']);
    $ingreso=trim($_POST['txtingreso']);
    $inbreak=trim($_POST['txteinbreak']);
    $fnbreak=trim($_POST['txtfnbreak']);
    $salida=trim($_POST['txtsalida']);
    $estado=trim($_POST['txtestado']);

///////// Fin informacion enviada por el formulario /// 

////////////// Insertar a la tabla la informacion generada /////////
$sql="insert into asis_empl (idemp, ingreso, inbreak, fnbreak, salida, estado) VALUES (:idemp, :ingreso, :inbreak, :fnbreak, :salida, 'Activo')";

$sql = $connect->prepare($sql);
    
$sql->bindParam(':idemp', $idemp, PDO::PARAM_STR, 25);
$sql->bindParam(':ingreso', $ingreso, PDO::PARAM_STR, 25);
$sql->bindParam(':inbreak', $inbreak, PDO::PARAM_STR, 25);
$sql->bindParam(':fnbreak', $fnbreak, PDO::PARAM_STR, 25);
$sql->bindParam(':salida', $salida, PDO::PARAM_STR, 25);

    
$sql->execute();

$lastInsertId = $connect->lastInsertId();
if($lastInsertId>0){
            echo '<script type="text/javascript">
swal("¡Registrado!", "Agregado correctamente", "success").then(function() {
            window.location = "../asistencia/mostrar.php";
        });
        </script>';

}
else{

        echo '<script type="text/javascript">
swal("Error!", "Error!", "error").then(function() {
            window.location = "../asistencia/mostrar.php";
        });
        </script>';

print_r($sql->errorInfo()); 
}
}// Cierra envio de guardado
?>
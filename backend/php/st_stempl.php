<?php  
require_once('../../backend/bd/ctconex.php');
 if(isset($_POST['staddempl']))
 {
  
    $dniem=$_POST['txtnume'];
    $nomem=$_POST['txtnome'];
    $apeem=$_POST['txtapell'];
    $programa=$_POST['txtprog'];
    $coord=$_POST['txtcoord'];
    $idcar=$_POST['txtcar'];
    $estado=$_POST['txtest'];
    
  if(empty($dniem)){
   $errMSG = "Please enter your inicio.";
  }

  $stmt = "SELECT * FROM empleado  WHERE dniem='$dniem'";


   if(empty($dniem)) {
             echo '<script type="text/javascript">
swal("Error!", "Ya existe el registro a agregar!", "error").then(function() {
            window.location = "../empleado/mostrar.php";
        });
        </script>';
         }

         else
         {  // Validaremos primero que el document no exista
            $sql="SELECT * FROM empleado  WHERE dniem='$dniem'";
            

            $stmt = $connect->prepare($sql);
            $stmt->execute();

            if ($stmt->fetchColumn() == 0) // Si $row_cnt es mayor de 0 es porque existe el registro
            {
                if(!isset($errMSG))
  {
   $stmt = $connect->prepare("INSERT INTO empleado(dniem, nomem,apeem,idcar,programa,coord,estado) VALUES(:dniem,:nomem ,:apeem,:idcar,:programa,:coord,:estado)");
   $stmt->bindParam(':dniem',$dniem);
   $stmt->bindParam(':nomem',$nomem);
   $stmt->bindParam(':apeem',$apeem);
   $stmt->bindParam(':idcar',$idcar);
   $stmt->bindParam(':programa',$programa);
   $stmt->bindParam(':coord',$coord);
   $stmt->bindParam(':estado',$estado);
   if($stmt->execute())
   {
    echo '<script type="text/javascript">
swal("¡Registrado!", "Agregado correctamente", "success").then(function() {
            window.location = "../empleado/mostrar.php";
        });
        </script>';
   }
   else
   {
    $errMSG = "error while inserting....";
   }

  } 
            }

                else{

                     echo '<script type="text/javascript">
swal("Error!", "ya existe el registro!", "error").then(function() {
            window.location = "../empleado/mostrar.php";
        });
        </script>';

 // if no error occured, continue ....

}
  

  }
 
 }
?>
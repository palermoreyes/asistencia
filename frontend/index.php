<?php
session_start();
    if (isset($_SESSION['id'])){
        header('Location: administrador/escritorio.php');
    } 
include_once '../backend/php/ctlogx.php'
 ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Acceso al sistema</title>
<link rel="stylesheet" type="text/css" href="../backend/css/style.css">
<link rel="shortcut icon" href="../backend/img/ico.png" />
</head>
<body>
  <div class="card">
   <form autocomplete="off" method="post"  role="form">

      <h2 class="title"> Acceso al sistema</h2>
       <?php 
                            if (isset($errMsg)) {
                                echo '
    <div style="color:#FF0000;text-align:center;font-size:20px; font-weight:bold;">'.$errMsg.'</div>
    ';  ;
                            }

                        ?>
      <br>
      <div class="email-login">
         
         <input type="text" name="usuario" value="<?php if(isset($_POST['usuario'])) echo $_POST['usuario'] ?>"  autocomplete="off" placeholder="Ingresa nombre de usuario" name="uname" required>
        
         <input type="password" name="clave" value="<?php if(isset($_POST['clave'])) echo MD5($_POST['clave']) ?>" placeholder="Ingresa contraseña" name="psw" required>
      </div>
      <button class="cta-btn" name='ctxlog' type="submit">Acceder</button>
      
   </form>
</div>
<script type="text/javascript" src="../backend/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../backend/js/reenvio.js"></script>
</body>
</html>

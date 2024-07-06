<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Asistencia | Formación</title>
  <link rel="stylesheet" type="text/css" href="../../backend/css/style.css">
  <link rel="shortcut icon" href="../../backend/img/ico.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  
  <style type="text/css"> 
    .badge {
      background-color: #2bb516;
      color: white;
      padding: 4px 8px;
      text-align: center;
      border-radius: 5px;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-image: url('https://i.postimg.cc/GhyLfyhz/Fondo-fractalia.webp');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      background-attachment: fixed;
    }

    .content {
      width: 100%;
    }

    .title1 {
      font-size: 20px;
      font-weight: bold;
      padding-left: 10px;
      padding-top: 10px;   
      padding-bottom: 10px;
      background-color: steelblue;
      width: 97%;
    }

    .left {
      padding-left: 10px;
      padding-top: 10px;
      margin-left: 57px;
      float: left;
      position: relative;
      width: 45%;
      border: steelblue solid 1px;
      height: auto;
    }

    .right {
      padding-top: 10px;
      padding-left: 10px;
      margin-left: 10px;
      position: relative;
      float: left;
      width: 45%;
      border: steelblue solid 1px;
      height: auto;
    }
    
    .version {
      text-align: center;
      margin-top: 20px;
      color: #000000;
      font-size: 12px;
       /* Cambiar el color del texto a blanco */
    }

  .a {
    color: #ffffff; /* Color del texto blanco */
    text-decoration: none; /* Sin subrayado */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    
  }

  </style>
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh;">

<?php 
date_default_timezone_set ('America/Lima');
$fechaEntrada= date ("Y-m-d H:i:s");
?>

<div class="card" style="max-width: 400px; margin: 0 auto;">
  <form autocomplete="off" method="post" role="form">
    <br>
    <h2 class="title">Asistencia | Formación</h2>

    <div class="container-clock" style="text-align: center;">
      <h1 id="time">00:00:00</h1>
      <p id="date">Cargando...</p>
      <br>
    </div>

    <div class="email-login">
      <!-- Registrar Asistencias -->
      <a style="width: 250px; text-align: center; background-color: rgb(50 96 159); color: white; padding: 10px; display: block; margin: 10px auto;" href="formacion.php" class='cta-btn'><i class="fas fa-user-check"></i>  Registrar Asistencia</a>
      <!-- Registrar Evaluaciones -->
      <a style="width: 250px; text-align: center; background-color: rgb(50 96 159); color: white; padding: 10px; display: block; margin: 10px auto;" href="evaluaciones.php" class='cta-btn'><i class="fas fa-graduation-cap"></i>  Registrar Evaluaciones</a>
      <!-- Consultar Asistencias -->
      <a style="width: 250px; text-align: center; background-color: #2bb516; color: white; padding: 10px; display: block; margin: 10px auto;" href="detalle.php" class='cta-btn'><i class="fas fa-search"></i>  Ver Asistencias</a>
    </div>
  </form>
  <div class="version">
  <a>Versión 1.2</a>
</div>
</div>
<script>
    var serverTime = new Date('<?= $fechaEntrada ?>');
</script>
<script src="../../backend/js/clock.js"></script>
<script type="text/javascript" src="../../backend/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../../backend/js/reenvio.js"></script>
</body>
</html>

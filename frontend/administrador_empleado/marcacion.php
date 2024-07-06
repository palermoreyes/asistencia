<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Registro de Asistencia</title>
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

</style>
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh;">

  <div class="card">

    
<form autocomplete="off" method="post"  role="form">

      <br>
      <h2 class="title"> Ingresa Nº Documento </h2>

      <div class="container-clock" style="text-align: center;">
        <h1 id="time">00:00:00</h1>
        <p id="date">Cargando...</p>
       
    </div>
      <div class="email-login">
         

  <div class="form-group mb-2">  </div>
  <div class="form-group mx-sm-1 mb-1" style="display: inline-block;
    margin: 0 6px;">
    <input type="text" name="titulo" id="titulo" placeholder="Ingresa documento"  class="form-control">
    
    <input type="submit" name="BuscaTitulo" value="Buscar" class="btn btn-primary" style="background-color: #4CAF50; border: none; border-radius: 10px; color: white; padding: 8px 7px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; cursor: pointer;">

  </div>
</form> 




<?php
require '../../backend/bd/ctconex.php';

$BuscaTitulo = filter_input(INPUT_POST, 'BuscaTitulo', FILTER_SANITIZE_SPECIAL_CHARS);

// Inicializa la variable para almacenar el mensaje de inactividad, si es necesario
$mensajeInactividad = '';

// Tipo de solicitud obtenido de la URL
$tipoSolicitud = isset($_GET['id']) ? (int)$_GET['id'] : 0;


// Obtener la fecha actual
date_default_timezone_set('America/Lima');

$fechaEntrada = date('Y-m-d H:i:s');

// Obtener la dirección IP del usuario
$ipUsuario = $_SERVER['REMOTE_ADDR'];

// Capturar el tipo de dispositivo desde el formulario
$deviceType = filter_input(INPUT_POST, 'deviceType', FILTER_SANITIZE_SPECIAL_CHARS);


if ($BuscaTitulo) {
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
    $resultado_titulo = "SELECT * FROM empleado WHERE dniem = '$titulo' ORDER BY dniem ASC LIMIT 7";
    $resultado_contenido = $connect->prepare($resultado_titulo);
    $resultado_contenido->execute();

    $usuarioEncontrado = false;
    $mensajeInactividad = '';

    while ($registros = $resultado_contenido->fetch(PDO::FETCH_ASSOC)) {
        if ($registros['estado'] === 'Activo') {
            $usuarioEncontrado = true;
            $idemp = $registros['idemp'];
            $nombre = $registros['nomem'];


        // Mostrar información del usuario
        echo '<input type="hidden" name="titulo" id="titulo" value="' . $idemp . '" class="form-control">';
        echo '<input type="hidden" name="ipUsuario" value="' . $ipUsuario . '">';
        echo '<span class="badge badge-secondary">¡Hola 👋 ' . htmlspecialchars($nombre) . '!</span>';


        // Validación específica para ID=1 (Ingreso)
        $stmtValidacionIngreso = $connect->prepare("SELECT 1 FROM asis_empl WHERE idemp = :idemp AND salida IS NULL AND estado = 'Activo'");
        $stmtValidacionIngreso->bindParam(':idemp', $idemp);
        $stmtValidacionIngreso->execute();
        $condicionesIngresoCumplidas = $stmtValidacionIngreso->rowCount() === 0;

        // Proceder con el registro si se cumplen las condiciones
        if ($condicionesIngresoCumplidas && $tipoSolicitud == 1) {
            echo "
                <form class='form-inline' method='POST' action=''>
                    <input type='hidden' name='txtora' id='fechad' class='form-control' value='$fechaEntrada'>
                    <input type='hidden' name='titulo' value='" . $idemp . "' id='titulo' placeholder='Ingresa documento' class='form-control'>
                    <input type='hidden' name='txtass' value='$tipoSolicitud'> <!-- Valor fijo para Ingreso -->
                    <input type='hidden' name='deviceType' id='deviceType' value=''>
                    <button class='cta-btn' name='staddasist'>REGISTRAR INGRESO</button>
                </form>";
        } elseif (!$condicionesIngresoCumplidas && $tipoSolicitud == 1) {
            echo '<br><div class="alert alert-warning" role="alert" style="background-color: #FC4F4F; color: #fff; border-color: #FF6347; text-align: center;">Ya cuentas con un ingreso que aún no haz finalizado.</div>';
        }

       // Lógica para id=2 (Inicio Break)
        if ($tipoSolicitud == 2) {
            // Verificar condiciones para el registro
            $stmtValidacionInbreak = $connect->prepare("SELECT idasem FROM asis_empl WHERE idemp = :idemp AND inbreak IS NULL AND fnbreak IS NULL AND salida IS NULL AND estado = 'Activo' ORDER BY idasem DESC LIMIT 1");
            $stmtValidacionInbreak->bindParam(':idemp', $idemp);
            $stmtValidacionInbreak->execute();
            $condicionesInbreakCumplidas = $stmtValidacionInbreak->rowCount() > 0;

            // Proceder con el registro si se cumplen las condiciones
            if ($condicionesInbreakCumplidas) {
                renderForm('INICIAR BREAK', 'INICIAR BREAK', $fechaEntrada, $idemp, $tipoSolicitud, $ipUsuario, $deviceType);
            } else {
    echo '<br><div class="alert alert-warning" role="alert" style="background-color: #FC4F4F; color: #fff; border-color: #FF6347; text-align: center;">No haz marcado tu inicio de Jornada o ya marcaste inicio de Break.</div>';
}
        }

        // Validación específica para ID=3 (Fin Break)
        $stmtValidacionFinBreak = $connect->prepare("SELECT idasem FROM asis_empl WHERE idemp = :idemp AND ingreso IS NOT NULL AND inbreak IS NOT NULL AND fnbreak IS NULL AND salida IS NULL AND estado = 'Activo'");
        $stmtValidacionFinBreak->bindParam(':idemp', $idemp);
        $stmtValidacionFinBreak->execute();
        $condicionesFinBreakCumplidas = $stmtValidacionFinBreak->rowCount() > 0;

        // Proceder con el registro si se cumplen las condiciones
        if ($condicionesFinBreakCumplidas && $tipoSolicitud == 3) {
            echo "
                <form class='form-inline' method='POST' action=''>
                    <input type='hidden' name='txtora' id='fechad' class='form-control' value='$fechaEntrada'>
                    <input type='hidden' name='titulo' value='" . $idemp . "' id='titulo' placeholder='Ingresa documento' class='form-control'>
                    <input type='hidden' name='txtass' value='$tipoSolicitud'> <!-- Valor fijo para Fin Break -->
                    <button class='cta-btn' name='staddasist'>FIN DE BREAK</button>
                </form>";
        } elseif (!$condicionesFinBreakCumplidas && $tipoSolicitud == 3) {
            echo '<br><div class="alert alert-warning" role="alert" style="background-color: #FC4F4F; color: #fff; border-color: #FF6347; text-align: center;">No haz marcado tu inicio de Break o ya marcaste Fin de Break.</div>';
        }

        // Validación específica para ID=4 (Salida)
        $stmtValidacionSalida = $connect->prepare("SELECT idasem FROM asis_empl WHERE idemp = :idemp AND ingreso IS NOT NULL AND inbreak IS NOT NULL AND fnbreak IS NOT NULL AND salida IS NULL AND estado = 'Activo'");
        $stmtValidacionSalida->bindParam(':idemp', $idemp);
        $stmtValidacionSalida->execute();
        $condicionesSalidaCumplidas = $stmtValidacionSalida->rowCount() > 0;

        // Proceder con el registro si se cumplen las condiciones
        if ($condicionesSalidaCumplidas && $tipoSolicitud == 4) {
            echo "
                <form class='form-inline' method='POST' action=''>
                    <input type='hidden' name='txtora' id='fechad' class='form-control' value='$fechaEntrada'>
                    <input type='hidden' name='titulo' value='" . $idemp . "' id='titulo' placeholder='Ingresa documento' class='form-control'>
                    <input type='hidden' name='txtass' value='$tipoSolicitud'> <!-- Valor fijo para Salida -->
                    <button class='cta-btn' name='staddasist'>REGISTRAR SALIDA</button>
                </form>";
        } elseif (!$condicionesSalidaCumplidas && $tipoSolicitud == 4) {
            echo '<br><div class="alert alert-warning" role="alert" style="background-color: #FC4F4F; color: #fff; border-color: #FF6347; text-align: center;">No haz marcado tu fin de Break o ya cuentas con una Salida.</div>';
        }

        $usuarioEncontrado = true;
    }

        // Validación específica para ID=5 (Consultar Marcación)

        if ($tipoSolicitud == 5) {
            $stmtConsulta = $connect->prepare("SELECT x1.ingreso, x1.inbreak, x1.fnbreak, x1.salida FROM empleado x0 LEFT JOIN asis_empl x1 ON x0.idemp = x1.idemp WHERE x0.idemp = :idemp ORDER BY x1.idasem DESC 
            LIMIT 1");
            $stmtConsulta->bindParam(':idemp', $idemp);
            $stmtConsulta->execute();
        
            if ($stmtConsulta->rowCount() > 0) {
                $registro = $stmtConsulta->fetch(PDO::FETCH_ASSOC);
        
                echo "<div class='container'>";
                echo "<div class='data-field' style='text-align: center; font-weight: bold;'>Detalles De Tú Última Marcación</div>";
                echo "<div class='data-field'><br>";
        
                // Mostrar cada campo o una alerta si es nulo
                echo "<div><strong>Ingreso: </strong>" . (!empty($registro['ingreso']) ? htmlspecialchars($registro['ingreso']) : "<span class='no-record'>¡Sin Registro!</span>") . "</div>";
                echo "<div><strong>Inicio Break: </strong>" . (!empty($registro['inbreak']) ? htmlspecialchars($registro['inbreak']) : "<span class='no-record'>¡Sin Registro!</span>") . "</div>";
                echo "<div><strong>Fin Break: </strong>" . (!empty($registro['fnbreak']) ? htmlspecialchars($registro['fnbreak']) : "<span class='no-record'>¡Sin Registro!</span>") . "</div>";
                echo "<div><strong>Salida: </strong>" . (!empty($registro['salida']) ? htmlspecialchars($registro['salida']) : "<span class='no-record'>¡Sin Registro!</span>") . "</div>";
                echo "</div>";
                echo "<div class='data-field' style='text-align: center; font-weight: bold; font-style: italic; font-size: 12px;'><br><br>¡No olvides marcar todos tus eventos, que tengas buen día 🤗!</div>";
            } else {
                echo '<div class="alert alert-warning">No se encontraron registros para este usuario.</div>';

            }

        } else {
            // Usuario inactivo, captura el mensaje de inactividad
            $mensajeInactividad = $registros['obs'];
        }
    }
        
    // Mostrar la alerta si no se encontraron usuarios
    if (!$usuarioEncontrado) {
        echo '<br><div class="alert alert-warning" role="alert" style="background-color: #FC4F4F; color: #fff; border-color: #FF6347; text-align: center;">¡Usuario no encontrado o inactivo, contacte con su Responsable!';
        if (!empty($mensajeInactividad)) {
            echo '<br><span style="display: inline-block; margin-top: 10px; padding: 5px; background-color: #FFD700; color: #000; border-radius: 5px; font-weight: bold;">Motivo de inactividad: ' . htmlspecialchars($mensajeInactividad) . '</span>';
        }
        echo '</div>';
        echo '<script>
                setTimeout(function(){
                    window.location.reload(1);
                }, 5000); // 5000 milisegundos = 5 segundos
              </script>';
    }
}


// Función para renderizar el formulario
function renderForm($actionName, $buttonText, $fechaEntrada, $idemp, $tipoSolicitud, $ipUsuario, $deviceType) {
    echo "
        <form class='form-inline' method='POST' action=''>
            <input type='hidden' name='txtora' id='fechad' class='form-control' value='$fechaEntrada'>
            <input type='hidden' name='titulo' value='" . $idemp . "' id='titulo' placeholder='Ingresa documento' class='form-control'>
            <input type='hidden' name='txtass' value='$tipoSolicitud'>
            <input type='hidden' name='ipUsuario' value='$ipUsuario'>
            <input type='hidden' name='deviceType' id='deviceType' value=''>
            <button class='cta-btn' name='staddasist'>$buttonText</button>
        </form>";
}
?>



</div>

<div>
    <!-- Botón HOME -->

<br><br>
<a href="index.php" class="cta-link"><i class="fas fa-home"></i> Volver al Inicio</a>

<style>
.cta-link {
  display: inline-block;
  padding: 8px 16px;
  background-color: rgb(50 96 159);
  color: white;
  text-decoration: none;
  font-size: 16px;
  text-align: center;
  cursor: pointer;
  border-radius: 10px;
  border: none;
  width: 270px;
  align-items: center;

}

.cta-link:hover {
  background-color: rgb(50 96 159);
}

.container {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            margin-top: 15px;
        }
        .alert {
            color: white;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .alert-warning {
            background-color: #fc4f4f;
        }

        .no-record {
            color: #ff0000;
        }
      </style>

</div>

<script>
    var serverTime = new Date('<?= $fechaEntrada ?>');
</script>

<script>

document.addEventListener("DOMContentLoaded", function() {
    var deviceType = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? 'Mobile' : 'PC';
    document.getElementById('deviceType').value = deviceType;
});

</script>

<script src="../../backend/js/clock.js"></script>
<script type="text/javascript" src="../../backend/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../../backend/js/reenvio.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="../../backend/js/sweetalert.js"></script>

<?php
    include_once '../../backend/php/st_addasis.php'
?>

</body>
</html>

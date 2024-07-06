<?php
session_start();
require_once '../../backend/php/funcionesEvaluaciones.php';  // Asegúrate de que esta ruta es correcta

// Inicializa variables
$dniFormador = isset($_POST['dniFormador']) ? filter_input(INPUT_POST, 'dniFormador', FILTER_SANITIZE_SPECIAL_CHARS) : "";
$grupoSeleccionado = isset($_POST['grupo']) ? filter_input(INPUT_POST, 'grupo', FILTER_SANITIZE_SPECIAL_CHARS) : "";
$grupos = [];
$usuarios = [];
$mensaje = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['buscarFormador']) && !empty($dniFormador)) {
        $grupos = obtenerGruposPorFormador($dniFormador);
    }

    if (isset($_POST['seleccionarGrupo']) && !empty($grupoSeleccionado)) {
        $usuarios = obtenerUsuariosPorGrupo($grupoSeleccionado);
    }

    if (isset($_POST['guardarEvaluaciones'])) {
        foreach ($_POST['evaluaciones'] as $idUsuario => $evaluaciones) {
            foreach ($evaluaciones as $indice => $valor) {
                // Convertir cadena vacía a null para la base de datos
                $evaluaciones[$indice] = $valor === '' ? null : floatval($valor);
            }
            foreach ($evaluaciones as $eval => $valor) {
                if ($valor < 0 || $valor > 20) {
                    // Manejar valores inválidos, por ejemplo, estableciendo un mensaje de error
                    $_SESSION['mensaje'] = ['texto' => 'Error: Todos los valores de las evaluaciones deben estar entre 0 y 20.', 'tipo' => 'error'];
                    header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]));
                    exit();
                }
            }
            $estadoFinal = $_POST['estadoFinal'][$idUsuario];
            $estado = $_POST['estado'][$idUsuario];
            if (guardarEvaluaciones($idUsuario, $evaluaciones, $estadoFinal, $estado)) {
                $_SESSION['mensaje'] = ['texto' => 'Evaluaciones guardadas correctamente.', 'tipo' => 'success'];
            } else {
                $_SESSION['mensaje'] = ['texto' => 'Error al guardar evaluaciones.', 'tipo' => 'error'];
            }
        }
        header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]));
        exit();
    }
    
}

// Después de procesar el POST, verifica si hay un mensaje en sesión para mostrar
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);  // Limpia el mensaje de la sesión después de almacenarlo localmente
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Formación - Acceso a aplicativos</title>
    <link rel="stylesheet" href="../../backend/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../backend/css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link rel="shortcut icon" href="../../backend/img/ico.png" />
    <link rel="stylesheet" type="text/css" href="../../backend/css/datatable.css">
    <link rel="stylesheet" type="text/css" href="../../backend/css/buttonsdataTables.css">
    <link rel="stylesheet" type="text/css" href="../../backend/css/font.css">
    <script src="../../backend/js/sweetalert.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="../../backend/js/jquery-3.3.1.slim.min.js"></script>
    <script src="../../backend/js/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <style>

    .scroll-horizontal {
        overflow-x: auto;
    }
    
    .table-responsive {
        min-width: 3500px;
    }

    .table thead th {
        background-color: #6c757d !important; 
        color: white !important;
        text-align: center !important;
        padding: 10px !important;
        border: 1px solid #ddd !important;
    }

    .table tbody td {
        text-align: center;
        padding: 8px;
    }

    .form-control {
        
    }

    .titulo-centrado {
        text-align: center;
        font-weight: bold;
 
    }

    .my-custom-container {
    max-width: 2000px;
    }
    .estado-final {
    display: block;
    padding: 5px;
    text-align: center;
    color: white;
    font-weight: bold;
    }

    .aprobado {
        background-color: #28a745; /* Verde, similar a Bootstrap .bg-success */
    }

    .desaprobado {
        background-color: #dc3545; /* Rojo, similar a Bootstrap .bg-danger */
    }



</style>

</head>
<body>
<div class="container my-custom-container mt-5">
<h2 class="titulo-centrado">ACCESOS A APLICATIVOS | FORMACIÓN</h2>
    <div class="row justify-content-center">
        <!-- Botón de Buscar DNI -->
        <div class="col-md-3 d-flex align-items-center justify-content-between">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="d-flex">
                <input type="text" class="form-control" name="dniFormador" id="dniFormador" placeholder="Ingresa DNI Formador" required>
                <button type="submit" class="btn btn-secondary ml-2" name="buscarFormador">Buscar</button>
            </form>
        </div>
        
        <!-- Botón de Seleccionar Grupo, asumiendo que $dniFormador y $grupos están definidos -->
        <?php if (!empty($dniFormador) && !empty($grupos)): ?>
            <div class="col-md-3 d-flex align-items-center justify-content-between">
            <span>Grupo: </span>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="d-flex flex-grow-1">
            <input type="hidden" name="dniFormador" value="<?php echo $dniFormador; ?>">
                <select class="form-control flex-grow-1" name="grupo" id="grupo" required>
                    <?php foreach ($grupos as $grupo): ?>
                    <option value="<?php echo $grupo['grupo']; ?>"><?php echo $grupo['grupo']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-secondary ml-2" name="seleccionarGrupo">Seleccionar</button>
            </form>
        </div>

        <?php endif; ?>

        <!-- Botón Volver al Inicio -->
        <div class="col-md-1 d-flex align-items-center justify-content-center">
            <a href="index.php" class="btn btn-success"><i class="fas fa-home"></i> IR AL INICIO</a>
        </div>
    </div>
    <div><br></div>

    
    <!-- Mostrar tabla de usuarios y evaluaciones si hay datos -->
    <?php if (!empty($usuarios)): ?>
        <div class="scroll-horizontal">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>DNI</th>
                    <th>Nombres</th>
                    <th>Grupo</th>
                    <th>Estado</th>
                    <th>Correo corporativo</th>
                    <th>Semilla</th>
                    <th>Vpn</th>
                    <th>RED</th>
                    <th>Citrix</th>
                    <th>Believe</th>
                    <th>DEM</th>
                    <th>B2B</th>
                    <th>Salesforce</th>
                    <th>Acad. Movistar RUT</th>
                    <th>Web Fraude</th>
                    <th>Remedy</th>
                    <th>Genesys/Place</th>
                    <th>SISON</th>
                    <th>T-Ayudo</th>
                    <th>SGI</th>
                    <th>Asistencia</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Supongamos que $usuarios se ha definido y está lleno con los datos de los usuarios que quieres mostrar -->
                <?php foreach ($usuarios as $indice => $usuario): ?>
                <tr>
                    <td><?php echo $indice + 1; ?></td>
                    <td><?php echo $usuario['dni']; ?></td>
                    <td><?php echo $usuario['nombres']; ?></td>
                    <td><?php echo $usuario['grupo']; ?></td>
                    <td><?php echo $usuario['estado']; ?></td>
                    <td>
                        <input type="email" class="form-control" name="correo_corporativo[<?php echo $usuario['dni']; ?>]" placeholder="Correo corporativo">
                    </td>
                    <!-- A partir de aquí se agregan los desplegables para cada aplicación -->
                    <?php 
                            // Lista de herramientas
                            $herramientas = ['Semilla', 'Vpn', 'RED', 'Citrix', 'Believe', 'DEM', 'B2B', 'Salesforce', 'Academia Movistar rut', 'Web Fraude', 'Remedy', 'Genesys o Place', 'SISON', 'T-Ayudo', 'SGI', 'Asistencia'];
                            foreach ($herramientas as $herramienta): ?>
                            <td>
                                <select class="form-control" name="<?php echo strtolower(str_replace(' ', '_', $herramienta)) . "[$usuario[dni]]"; ?>">
                                    <option value="-"></option>    
                                    <option value="Ok">Ok</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Error">Error</option>
                                </select>
                            </td>
                            <?php endforeach; ?>
                            <td>
                                <input type="text" class="form-control" name="observaciones[<?php echo $usuario['dni']; ?>]" placeholder="Observaciones">
                            </td>
                        </tr>
                        <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-right">
            <button type="submit" class="btn btn-primary" name="registrarAcceso">Registrar Acceso</button>
        </div>
        <div><br></div>
    </form>
    <?php endif; ?>
</div>

    <?php if ($mensaje): ?>
<script>
$(document).ready(function() {
    Swal.fire({
        title: '<?php echo $mensaje["tipo"] === "success" ? "Éxito" : "Advertencia"; ?>',
        text: '<?php echo $mensaje["texto"]; ?>',
        icon: '<?php echo $mensaje["tipo"]; ?>',
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el mensaje es de éxito o alerta o si es un error de asistencia ya registrada, redirige a index.php
            if ('<?php echo $mensaje["tipo"]; ?>' === 'success' || '<?php echo $mensaje["tipo"]; ?>' === 'warning') {
                window.location = 'index.php';
            }
        }
    });
});
</script>

<?php endif; ?>


</div> 
<script src="../../backend/js/popper.min.js"></script>
<script src="../../backend/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../../backend/js/datatable.js"></script>
<script type="text/javascript" src="../../backend/js/datatablebuttons.js"></script>
<script type="text/javascript" src="../../backend/js/jszip.js"></script>
<script type="text/javascript" src="../../backend/js/pdfmake.js"></script>
<script type="text/javascript" src="../../backend/js/vfs_fonts.js"></script>
<script type="text/javascript" src="../../backend/js/buttonshtml5.js"></script>
<script type="text/javascript" src="../../backend/js/buttonsprint.js"></script>

</body>
</html>
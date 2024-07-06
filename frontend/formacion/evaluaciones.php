<?php
session_start();
require_once '../../backend/php/funcionesEvaluaciones.php';

// Inicialización de variables
$dniFormador = filter_input(INPUT_POST, 'dniFormador', FILTER_SANITIZE_SPECIAL_CHARS);
$grupoSeleccionado = filter_input(INPUT_POST, 'grupo', FILTER_SANITIZE_SPECIAL_CHARS);
$grupos = [];
$usuarios = [];
$mensaje = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_log("Datos POST recibidos: " . print_r($_POST, true));

    if (!empty($dniFormador) && isset($_POST['buscarFormador'])) {
        $grupos = obtenerGruposPorFormador($dniFormador);
    }

    if (!empty($grupoSeleccionado) && isset($_POST['seleccionarGrupo'])) {
        $usuarios = obtenerUsuariosPorGrupo($grupoSeleccionado);
    }

    if (isset($_POST['guardarEvaluaciones'])) {
        error_log("Procesando guardarEvaluaciones...");

        foreach ($_POST['evaluaciones'] as $idUsuario => $evaluaciones) {
            $evaluacionesLimpio = array_map(function($val) {
                return is_numeric($val) ? floatval($val) : null;
            }, $evaluaciones);

            $promedio = isset($_POST['promedios'][$idUsuario]) ? floatval($_POST['promedios'][$idUsuario]) : 0;

            error_log("Evaluaciones limpias para usuario $idUsuario: " . print_r($evaluacionesLimpio, true));
            error_log("Promedio recibido para usuario $idUsuario: $promedio");

            $valorInvalido = array_filter($evaluacionesLimpio, function($valor) {
                return $valor !== null && ($valor < 0 || $valor > 20);
            });

            if (!empty($valorInvalido)) {
                $_SESSION['mensaje'] = ['texto' => 'Error: Todos los valores de las evaluaciones deben estar entre 0 y 20.', 'tipo' => 'error'];
                continue; // Continuar con el siguiente usuario en lugar de hacer exit.
            }

            $estadoFinal = $_POST['estadoFinal'][$idUsuario] ?? 'Desconocido';
            $estado = $_POST['estado'][$idUsuario] ?? 'Pendiente';

            if (guardarEvaluaciones($idUsuario, $dniFormador, $grupoSeleccionado, $estado, $evaluacionesLimpio, $promedio, $estadoFinal)) {
                $_SESSION['mensaje'] = ['texto' => 'Evaluaciones guardadas correctamente.', 'tipo' => 'success'];
            } else {
                $_SESSION['mensaje'] = ['texto' => 'Error al guardar evaluaciones.', 'tipo' => 'error'];
            }
        }
        header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]));
        exit();
    }
    
}

// Manejo de mensajes de sesión
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Formación - Registro de Evaluaciones</title>
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

    .titulo-centrado {
        text-align: center;
        font-weight: bold;
 
    }

    .my-custom-container {
    max-width: 1800px;
    }

    input.estado-final {
    font-weight: bold;
    text-align: center;
    padding: 8px;
    color: white; /* Texto blanco */
    }

    input.estado-final.aprobado {
        background-color: #28a745; /* Verde */
    }

    input.estado-final.desaprobado {
        background-color: #dc3545; /* Rojo */
    }

    input.estado-final.retirado {
    background-color: #6c757d; /* Gris oscuro */
    color: white; /* Texto blanco */
    font-weight: bold; /* Texto en negrita para destacar */
}


</style>

    <script>
    $(document).ready(function() {
    // Deshabilita los campos para usuarios inactivos y ajusta su estado inicial
    $('.inactivo').find('input[name^="evaluaciones"], input[name^="estadoFinal"]').each(function() {
        $(this).attr('disabled', true);
        if ($(this).attr('name').startsWith('estadoFinal')) {
            $(this).val('Retirado').removeClass('aprobado desaprobado').addClass('retirado');
        }
    });

    // Función para calcular y actualizar el promedio y estado final
    function updateEvaluationData(userId) {
    var sum = 0;
    var count = 0;
    $('input[name^="evaluaciones[' + userId + ']"]').each(function() {
        var val = parseFloat($(this).val());
        if (!isNaN(val) && val >= 0 && val <= 20) {
            sum += val;
            count++;
        }
    });

    var promedio = count > 0 ? (sum / count).toFixed(2) : "0";
    $('#promedio-' + userId).text(promedio); // Actualizar visualmente el promedio
    $('input[name="promedios[' + userId + ']"]').val(promedio);

    var estadoFinal = parseFloat(promedio) >= 12 ? 'Aprobado' : 'Desaprobado';
    $('input[name="estadoFinal[' + userId + ']"]').val(estadoFinal).removeClass('aprobado desaprobado').addClass(estadoFinal.toLowerCase());
    }

    // Evento que se dispara cuando se cambia cualquier valor de evaluación
    $('input[name^="evaluaciones"]').change(function() {
        var userId = $(this).data('user-id');
        if (!$(this).attr('disabled')) {  // Solo actualizar si el campo no está deshabilitado
            updateEvaluationData(userId);
        }
        });
    });

    </script>


</head>
<body>
<div class="container my-custom-container mt-5">
<h2 class="titulo-centrado">REGISTRO DE EVALUACIONES | FORMACIÓN</h2>
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
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>DNI</th>
                    <th>Nombres</th>
                    <th>Grupo</th>
                    <th>Estado</th>
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <th style="width: 100px; min-width: 100px; max-width: 100px;">Eval. <?php echo $i; ?></th>
                    <?php endfor; ?>
                    <th>Promedio</th>
                    <th>Estado Final</th>
                </tr>
            </thead>
            <tbody>

    <?php foreach ($usuarios as $indice => $usuario): ?>
        <tr class="<?php echo $usuario['estado'] == 'Inactivo' ? 'inactivo' : ''; ?>">
            <td><?php echo $indice + 1; ?></td>
            <td><?php echo $usuario['dni']; ?></td>
            <td><?php echo $usuario['nombres']; ?></td>
            <td><?php echo $usuario['grupo']; ?></td>
            <td><?php echo $usuario['estado']; ?></td>
            <?php
                $total = 0;
                $count = 0; // Contador para las evaluaciones efectivamente ingresadas
                for ($i = 1; $i <= 6; $i++):
                    $eval = isset($usuario["evaluacion$i"]) ? $usuario["evaluacion$i"] : '';
                    $disabled = ($eval !== '') ? 'disabled' : ''; // Deshabilita el campo si ya tiene valor
                    if ($eval !== '') {
                        $total += $eval;
                        $count++;
                    }
            ?>
                <td>
                <input type="number" class="form-control" data-user-id="<?php echo $usuario['id_user']; ?>"
                    name="evaluaciones[<?php echo $usuario['id_user']; ?>][<?php echo $i; ?>]"
                    value="<?php echo isset($usuario["evaluacion$i"]) ? $usuario["evaluacion$i"] : ''; ?>" min="0" max="20" step="1" placeholder="">
                </td>
            <?php endfor; ?>
            <td id="promedio-<?php echo $usuario['id_user']; ?>"><?php echo ($count > 0) ? round($total / $count, 2) : "0"; ?>
            <input type="hidden" name="promedios[<?php echo $usuario['id_user']; ?>]" value="">

            </td>
            <td>
                <input type="text" class="form-control estado-final" name="estadoFinal[<?php echo $usuario['id_user']; ?>]" data-user-id="<?php echo $usuario['id_user']; ?>" value="<?php echo htmlspecialchars($estadoFinal ?? ''); ?>" readonly>
            </td>

        </tr>
    <?php endforeach; ?>
</tbody>

        </table>
        <div class="text-right">
            <button type="submit" class="btn btn-success" name="registrarEvaluaciones">REGISTRAR EVALUACIONES</button>
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
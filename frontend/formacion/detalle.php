<?php
session_start();
require_once '../../backend/php/funcionesFormacion.php';

// Inicializa variables
$dniFormador = "";
$grupoSeleccionado = "";
$fechaConsulta = date('Y-m-d'); // Puedes ajustar esto según necesites
$grupos = [];
$detallesAsistencia = [];
$mensaje = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['buscarFormador'])) {
        $dniFormador = filter_input(INPUT_POST, 'dniFormador', FILTER_SANITIZE_SPECIAL_CHARS);
        $grupos = obtenerGruposPorFormador($dniFormador);
    }
    if (isset($_POST['consultar'])) {
        $dniFormador = filter_input(INPUT_POST, 'dniFormador', FILTER_SANITIZE_SPECIAL_CHARS);
        $grupoSeleccionado = filter_input(INPUT_POST, 'grupo', FILTER_SANITIZE_SPECIAL_CHARS);
        $fechaConsulta = filter_input(INPUT_POST, 'fechaConsulta', FILTER_SANITIZE_SPECIAL_CHARS);
        $detallesAsistencia = obtenerDetallesAsistenciaPorFecha($dniFormador, $grupoSeleccionado, $fechaConsulta);
    }
}

// Después de obtener los detalles de asistencia
if (isset($_POST['consultar']) && empty($detallesAsistencia)) {
    $mensaje = ['texto' => 'No se encontraron registros para la fecha ingresada.', 'tipo' => 'warning'];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Detalle de Asistencia</title>
    <link rel="stylesheet" href="../../backend/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../backend/css/custom.css">
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
    max-width: 1600px;
    margin: 0 auto;

    }


</style>
</head>
<body>
<div class="container my-custom-container my-4">
    <h2 class="mb-4 titulo-centrado">DETALLE DE ASISTENCIA | FORMACIÓN</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="row justify-content-center">
        <!-- DNI Formador + Botón Buscar -->
        <div class="col-md-3">
            <div class="form-group d-flex align-items-center">
                <input type="text" class="form-control" name="dniFormador" id="dniFormador" placeholder ="Ingrese DNI Formador" value="<?php echo $dniFormador; ?>" required>
                <button type="submit" class="btn btn-secondary ml-2" name="buscarFormador">Buscar DNI</button>
            </div>
        </div>

        <!-- Grupo, si está disponible -->
        <?php if (!empty($grupos)): ?>
        <div class="col-md-3">
            <div class="form-group d-flex align-items-center">
                <label for="grupo" class="mb-0 mr-2">Grupo:</label>
                <select class="form-control" name="grupo" id="grupo">
                    <?php foreach ($grupos as $grupo): ?>
                        <option value="<?php echo $grupo['grupo']; ?>"<?php if ($grupoSeleccionado == $grupo['grupo']) echo ' selected="selected"'; ?>><?php echo $grupo['grupo']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php endif; ?>

        <!-- Fecha + Botón Consultar -->
        <div class="col-md-3">
            <div class="form-group d-flex align-items-center">
                <label for="fechaConsulta" class="mb-0 mr-2">Fecha:</label>
                <input type="date" class="form-control" name="fechaConsulta" id="fechaConsulta" value="<?php echo $fechaConsulta; ?>" required>
                <button type="submit" class="btn btn-primary ml-2" name="consultar">Consultar</button>
            </div>
        </div>
        
       <!-- Botón Nueva Asistencia -->
       <div class="col-md-3">
        <div class="form-group d-flex align-items-center">
            <a href="formacion.php" class="btn btn-success">+ NUEVA ASISTENCIA</a>
        </div>
    </div>
    </div>
</form>



    <?php if (!empty($detallesAsistencia)): ?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>DNI</th>
                    <th>Nombres</th>
                    <th>Formador</th>
                    <th>Programa</th>
                    <th>Grupo</th>
                    <th>Asistencia</th>
                    <th>Observaciones</th>
                    <th>Fecha Contrato</th>
                    <th>Motivo Deserción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detallesAsistencia as $indice => $asistencia): ?>
                <tr>
                    <td><?php echo $indice + 1; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($asistencia['fecha_asis'])); ?></td>
                    <td><?php echo $asistencia['dni']; ?></td>
                    <td><?php echo $asistencia['nombres']; ?></td>
                    <td><?php echo $asistencia['nom_formador']; ?></td>
                    <td><?php echo $asistencia['programa']; ?></td>
                    <td><?php echo $asistencia['grupo']; ?></td>
                    <td><?php echo $asistencia['asistencia']; ?></td>
                    <td>
                    <div class="text-preview" onclick="toggleText(this)">
                        <?php echo $asistencia['obs']; ?>
                    </div>
                    </td>
                    <td><?php echo $asistencia['fecha_contrato']; ?></td>
                    <td><?php echo $asistencia['desercion']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php if ($mensaje): ?>
<script>
$(document).ready(function() {
    Swal.fire({
        title: '<?php echo $mensaje["tipo"] === "success" ? "Éxito" : "Advertencia"; ?>',
        text: '<?php echo $mensaje["texto"]; ?>',
        icon: '<?php echo $mensaje["tipo"]; ?>',
        confirmButtonText: 'OK'
    });
});
</script>
<?php endif; ?>


</div>
<script src="../../backend/js/popper.min.js"></script>
<script src="../../backend/js/bootstrap.min.js"></script>
<script src="../../backend/js/jquery-3.3.1.slim.min.js"></script>
<script src="../../backend/js/jquery-3.3.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
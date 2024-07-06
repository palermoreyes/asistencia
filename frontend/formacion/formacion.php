<?php
session_start();
require_once '../../backend/php/funcionesFormacion.php';

// Inicializa variables
$dniFormador = isset($_POST['dniFormador']) ? filter_input(INPUT_POST, 'dniFormador', FILTER_SANITIZE_SPECIAL_CHARS) : "";
$grupoSeleccionado = isset($_POST['grupo']) ? filter_input(INPUT_POST, 'grupo', FILTER_SANITIZE_SPECIAL_CHARS) : "";
$periodoSeleccionado = isset($_POST['periodo']) ? filter_input(INPUT_POST, 'periodo', FILTER_SANITIZE_SPECIAL_CHARS) : "";
$fechaAsis = isset($_POST['fechaAsistencia']) ? $_POST['fechaAsistencia'] : date('Y-m-d');
$grupos = [];
$usuarios = [];
$mensaje = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['buscarFormador']) && !empty($dniFormador)) {
        $grupos = obtenerGruposPorFormador($dniFormador);
        if ($grupos === false) {
            $mensaje = ['texto' => 'Error al buscar formador.', 'tipo' => 'error'];
        } elseif (empty($grupos)) {
            $mensaje = ['texto' => 'No se encontraron grupos para el DNI proporcionado.', 'tipo' => 'warning'];
        }
    } elseif (isset($_POST['registrarAsistencias']) && !empty($grupoSeleccionado)) {
        $existeAsistenciaHoy = verificarExistenciaAsistenciaHoy($dniFormador, $grupoSeleccionado, $fechaAsis);
        if ($existeAsistenciaHoy) {
            $mensaje = ['texto' => 'Este grupo ya se ha registrado hoy. Verifica tus asistencias.', 'tipo' => 'warning'];
        } else {
            $ipUsuario = $_SERVER['REMOTE_ADDR'];
            $registroExitoso = true; // Asumimos éxito hasta que se demuestre lo contrario

            foreach ($_POST['asistencias'] as $idUsuario => $asistencia) {
                $observaciones = $_POST['observaciones'][$idUsuario] ?? '';
                $contrato = isset($_POST['contrato'][$idUsuario]) && $_POST['contrato'][$idUsuario] == 'SI' ? 'SI' : 'NO';
                $fechaContrato = $contrato === 'SI' && isset($_POST['fechaContrato'][$idUsuario]) ? $_POST['fechaContrato'][$idUsuario] : null;
                $motivoDesercion = $asistencia === 'C' && isset($_POST['motivoDesercion'][$idUsuario]) ? $_POST['motivoDesercion'][$idUsuario] : null;
        
                if (!registrarAsistencia($idUsuario, $dniFormador, $grupoSeleccionado, $periodoSeleccionado, $fechaAsis, $asistencia, $observaciones, $contrato, $fechaContrato, $motivoDesercion, $ipUsuario)) {
                    $registroExitoso = false;
                    break;
                }
            }

            if ($registroExitoso) {
                $_SESSION['mensaje'] = ['texto' => 'Asistencia registrada correctamente.', 'tipo' => 'success'];
                // Redirigir o realizar otra acción según sea necesario
            } else {
                $mensaje = ['texto' => 'Error al registrar asistencia.', 'tipo' => 'error'];
            }
        }
    }

    if (!empty($grupoSeleccionado)) {
        $usuarios = obtenerUsuariosPorGrupo($grupoSeleccionado);
    }
}

// Después de procesar el POST, verifica si hay un mensaje en sesión para mostrar
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']); // Limpia el mensaje de la sesión después de almacenarlo localmente
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Formación - Registro de Asistencia</title>
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
        padding-bottom: 0px;
    }

    .titulo-centrado {
        text-align: center;
        font-weight: bold;
 
    }

    .my-custom-container {
    max-width: 1800px;

    .radio-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    }

    .radio-option input[type="radio"] {
        margin-bottom: -5px; /* Espacio entre el botón radio y la etiqueta */
    }


}

</style>

<script>
function mostrarFechaContrato(checkbox, idUsuario) {
    var fechaContrato = document.getElementById('fechaContrato' + idUsuario);
    var encabezadoContrato = document.getElementById('encabezadoContrato');
    var checkboxesContrato = document.querySelectorAll("input[type='checkbox'][name^='contrato']");
    
    if (checkbox.checked) {
        fechaContrato.style.display = '';
        encabezadoContrato.style.display = 'table-cell'; // Asegura que el encabezado sea visible si algún checkbox es marcado
    } else {
        fechaContrato.style.display = 'none';
        // Verifica si algún otro checkbox aún está marcado
        var contratoMarcado = Array.from(checkboxesContrato).some(checkbox => checkbox.checked);
        encabezadoContrato.style.display = contratoMarcado ? 'table-cell' : 'none';
    }
}

function toggleAllContracts(source) {
    var checkboxesContrato = document.querySelectorAll('.contractCheckbox');
    checkboxesContrato.forEach(function(checkbox) {
        checkbox.checked = source.checked;
        mostrarFechaContrato(checkbox, checkbox.name.match(/\[(\d+)\]/)[1]); // Actualiza el display de la fecha según el estado del checkbox
    });
}
</script>



<script>
function mostrarMotivoDesercion(radioElement, idUsuario) {
    var motivoDesercion = document.getElementById('motivoDesercion' + idUsuario);
    var encabezadoCese = document.getElementById('encabezadoCese');
    var todosRadiosCese = document.querySelectorAll("input[type='radio'][value='C']"); // Selecciona todos los botones radio con valor 'C'
    
    // Determina si alguno de los radios de 'Cese' está seleccionado en cualquier usuario
    var ceseVisible = Array.from(todosRadiosCese).some(radio => radio.checked);

    if (radioElement.value === 'C') {
        motivoDesercion.style.display = 'table-cell'; // Muestra los campos de motivo si se selecciona 'C'
        encabezadoCese.style.display = 'table-cell';
    } else {
        motivoDesercion.style.display = 'none'; // Oculta los campos de motivo si se selecciona algo diferente de 'C'
        // Solo esconde el encabezado si ningún otro radio 'C' está seleccionado
        encabezadoCese.style.display = ceseVisible ? 'table-cell' : 'none';
    }
}
</script>



</head>
<body>
<div class="container my-custom-container mt-5">
    <h2 class="mt-5 titulo-centrado">REGISTRO DE ASISTENCIA | FORMACIÓN</h2>
    <div class="row justify-content-center">
        <!-- Ejemplo con el botón de Buscar DNI -->
        <div class="col-md-3 d-flex align-items-center justify-content-between">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="d-flex">
                <input type="text" class="form-control" name="dniFormador" id="dniFormador" placeholder="Ingresa DNI Formador" required>
                <button type="submit" class="btn btn-secondary ml-2" name="buscarFormador">Buscar</button>
            </form>
        </div>
        
        <!-- Ejemplo con el botón de Seleccionar Grupo, asumiendo que $dniFormador y $grupos están definidos -->
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

        <!-- Ejemplo con el campo desplegable Periodo -->
        <div class="col-md-3 d-flex align-items-center justify-content-between">
            <span>Periodo: </span>
            <select class="form-control" name="periodo" id="periodoSelect" required>
                <option value="">Elige una opción</option>
                <option value="FI" <?php echo (isset($_POST['periodo']) && $_POST['periodo'] == 'Formación Inicial') ? 'selected' : ''; ?>>Formación Inicial</option>
                <option value="PR" <?php echo (isset($_POST['periodo']) && $_POST['periodo'] == 'Práctica') ? 'selected' : ''; ?>>Práctica</option>
                <option value="OJT" <?php echo (isset($_POST['periodo']) && $_POST['periodo'] == 'OJT') ? 'selected' : ''; ?>>OJT</option>
            </select>
        </div>

        <!-- Fecha actual -->
        <div class="col-md-2 d-flex align-items-center justify-content-between">
    <span>Fecha: </span>
                <input type="date" class="form-control" id="fechaActual" value="<?php echo date('Y-m-d'); ?>">
</div>

        <!-- Botón Volver al Inicio -->
        <div class="col-md-1 d-flex align-items-center justify-content-center">
            <a href="index.php" class="btn btn-success"><i class="fas fa-home"></i> IR AL INICIO</a>
        </div>
    </div>
    <div><br></div>

    <!-- Condición para mostrar este bloque solo si hay usuarios disponibles -->
    <?php if (!empty($usuarios)): ?>
        <form id="formRegistro" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="dniFormador" value="<?php echo $dniFormador; ?>">
        <input type="hidden" name="grupo" value="<?php echo $grupoSeleccionado; ?>">
        <input type="hidden" name="periodo" id="hiddenPeriodoField">
        <input type="hidden" name="fechaAsistencia" id="hiddenFechaAsistencia">
        <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>DNI</th>
                    <th>Nombres</th>
                    <th>Formador</th>
                    <th>Programa</th>
                    <th>Grupo</th>
                    <th>Asistencia</th>
                    <th>Observaciones</th>
                    <th>Contrato  ||  Todos <input type="checkbox" id="selectAllContracts" onclick="toggleAllContracts(this)"></th>
                    <th id="encabezadoContrato" style="display: none;">Detalles</th>
                    <th id="encabezadoCese" style="display: none;">Detalles</th>
                

                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $indice => $usuario): ?>
                    <tr>
                        <td><?php echo $indice + 1; ?></td>
                        <td><?php echo $usuario['dni']; ?></td>
                        <td><?php echo $usuario['nombres']; ?></td>
                        <td><?php echo $usuario['nom_formador']; ?></td>
                        <td><?php echo $usuario['programa']; ?></td>
                        <td><?php echo $usuario['grupo']; ?></td>
                        <td>
                            <div style="display: flex; justify-content: space-around;">
                                <div class="radio-option">
                                    <input type="radio" id="asistio<?php echo $usuario['id_user']; ?>" name="asistencias[<?php echo $usuario['id_user']; ?>]" value="A" required checked onchange="mostrarMotivoDesercion(this, '<?php echo $usuario['id_user']; ?>')">
                                    <label for="asistio<?php echo $usuario['id_user']; ?>">A</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="falta<?php echo $usuario['id_user']; ?>" name="asistencias[<?php echo $usuario['id_user']; ?>]" value="F" onchange="mostrarMotivoDesercion(this, '<?php echo $usuario['id_user']; ?>')">
                                    <label for="falta<?php echo $usuario['id_user']; ?>">F</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="tardanza<?php echo $usuario['id_user']; ?>" name="asistencias[<?php echo $usuario['id_user']; ?>]" value="T" onchange="mostrarMotivoDesercion(this, '<?php echo $usuario['id_user']; ?>')">
                                    <label for="tardanza<?php echo $usuario['id_user']; ?>">T</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="cese<?php echo $usuario['id_user']; ?>" name="asistencias[<?php echo $usuario['id_user']; ?>]" value="C" onchange="mostrarMotivoDesercion(this, '<?php echo $usuario['id_user']; ?>')">
                                    <label for="cese<?php echo $usuario['id_user']; ?>">C</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="nsp<?php echo $usuario['id_user']; ?>" name="asistencias[<?php echo $usuario['id_user']; ?>]" value="NSP" onchange="mostrarMotivoDesercion(this, '<?php echo $usuario['id_user']; ?>')">
                                    <label for="nsp<?php echo $usuario['id_user']; ?>">N</label>
                                </div>
                            </div>
                        </td>

                        <td><input type="text" class="form-control" name="observaciones[<?php echo $usuario['id_user']; ?>]"></td>
                        <td>
                            <input type="checkbox" name="contrato[<?php echo $usuario['id_user']; ?>]" class="contractCheckbox" onchange="mostrarFechaContrato(this, <?php echo $usuario['id_user']; ?>)" value="SI">
                        </td>
                        <td style="display: none;" id="fechaContrato<?php echo $usuario['id_user']; ?>">
                            <input type="date" class="form-control" name="fechaContrato[<?php echo $usuario['id_user']; ?>]" value="<?php echo date('Y-m-d'); ?>">
                        </td>
                        
                        <td style="display: none;" id="motivoDesercion<?php echo $usuario['id_user']; ?>">
                            <select class="form-control" name="motivoDesercion[<?php echo $usuario['id_user']; ?>]">
                                <option value="">Elige un motivo</option>
                                <option value="No indica motivo">No indica motivo</option>
                                <option value="No cuenta con herramientas">No cuenta con herramientas</option>
                                <option value="Motivos personales">Motivos personales</option>
                                <option value="No cuenta con habilidades">No cuenta con habilidades</option>
                                <option value="Mejor oferta laboral">Mejor oferta laboral</option>
                                <option value="No son las condiciones ofrecidas">No son las condiciones ofrecidas</option>
                            </select>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-success" name="registrarAsistencias">REGISTRAR ASISTENCIA</button>
        </div>
        <div><br></div>
    </form>
    <?php endif; ?>

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

<script>
document.addEventListener("DOMContentLoaded", function() {
    var formulario = document.getElementById("formRegistro");

    if (formulario) {
        formulario.addEventListener("submit", function() {
            // Actualiza el valor del campo oculto de periodo, como ya lo haces
            var valorPeriodo = document.getElementById("periodoSelect").value;
            document.getElementById("hiddenPeriodoField").value = valorPeriodo;

            // Aquí actualizas el valor de la fecha de asistencia al campo oculto correspondiente
            var valorFechaAsis = document.getElementById("fechaActual").value; // Verifica que este ID sea correcto
            document.getElementById("hiddenFechaAsistencia").value = valorFechaAsis; // Asegúrate de que este ID sea correcto
            console.log("Valor de fecha capturado:", valorFechaAsis);

        });
    }
});

</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    var formulario = document.getElementById("formRegistro");

    formulario.addEventListener("submit", function(e) {
        var validacionExitosa = true;
        var periodoSelect = document.getElementById("periodoSelect");
        var valorPeriodo = periodoSelect.value;

        if (!periodoSelect || valorPeriodo === "") {
            alert("Por favor, selecciona un periodo válido.");
            periodoSelect.focus();
            validacionExitosa = false;
        }

        var selectsAsistencia = formulario.querySelectorAll("input[type='radio'][name^='asistencias']:checked");
        for (let radio of selectsAsistencia) {
            const idUsuario = radio.name.match(/\[(.*?)\]/)[1];
            if (radio.value === "C") {
                const motivoDesercionSelect = formulario.querySelector(`select[name='motivoDesercion[${idUsuario}]']`);
                if (motivoDesercionSelect && motivoDesercionSelect.value === "") {
                    alert("Por favor, selecciona un motivo de deserción para los casos de 'Cese'.");
                    motivoDesercionSelect.focus();
                    e.preventDefault(); // Detiene el envío del formulario
                    return;
                }
            }

            const checkboxContrato = formulario.querySelector(`input[name='contrato[${idUsuario}]']`);
            if (checkboxContrato && checkboxContrato.checked) {
                const fechaContratoInput = formulario.querySelector(`input[name='fechaContrato[${idUsuario}]']`);
                if (fechaContratoInput && fechaContratoInput.value === "") {
                    alert("Por favor, ingresa la fecha de contrato para todos los contratos marcados.");
                    fechaContratoInput.focus();
                    validacionExitosa = false;
                    break;
                }
            }
        }

        if (!validacionExitosa) {
            e.preventDefault(); // Detiene el envío del formulario
        }
    });
});
</script>



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
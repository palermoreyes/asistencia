<?php

if (isset($_POST['staddasist'])) {
    $idemp = trim($_POST['titulo']);
    $idasi = isset($_POST['txtass']) ? trim($_POST['txtass']) : null;
    $fere = trim($_POST['txtora']);
    $ipUsuario = $_SERVER['REMOTE_ADDR'];
    $ip = $ipUsuario; // Definición de la variable $ip
    $deviceType = isset($_POST['deviceType']) ? $_POST['deviceType'] : 'Unknown'; // Captura el modelo de dispositivo desde donde se estan ingresando

    if ($idasi !== null) {
        // Establecer valores predeterminados para los campos de asistencia
        $ingreso = null;
        $inbreak = null;
        $fnbreak = null;
        $salida = null;

        // Obtener el último registro para el mismo idemp
        $sqlUltimoRegistro = "SELECT * FROM asis_empl WHERE idemp = :idemp ORDER BY idasem DESC LIMIT 1";
        $stmtUltimoRegistro = $connect->prepare($sqlUltimoRegistro);
        $stmtUltimoRegistro->bindParam(':idemp', $idemp);
        $stmtUltimoRegistro->execute();
        $ultimoRegistro = $stmtUltimoRegistro->fetch(PDO::FETCH_ASSOC);

        if ($ultimoRegistro) {
            $idasem = $ultimoRegistro['idasem']; // Asumiendo que idasem es una columna en asis_empl
        }

        // Validar para el caso de ingreso
        if ($idasi == 1) {
            if ($ultimoRegistro && $ultimoRegistro['salida'] === null) {
                mostrarError("Existe un ingreso anterior que no se ha finalizado.");
                exit;
            }
        }

        // Actualizar o insertar según el ID de asistencia
        switch ($idasi) {
            case 1: // Ingreso
                $ingreso = $fere;
                // Asegúrate de incluir el campo `device` en tu consulta SQL
                $sql = "INSERT INTO asis_empl (idemp, ingreso, ip_login, device, estado) VALUES (:idemp, :ingreso, :ip, :device, 'Activo')";
                $stmt = $connect->prepare($sql);
                $stmt->bindParam(':idemp', $idemp);
                $stmt->bindParam(':ingreso', $ingreso);
                $stmt->bindParam(':ip', $ip);
                $stmt->bindParam(':device', $deviceType); // Vincular el tipo de dispositivo
                break;
            case 2: // Inicio de break
                if ($ultimoRegistro && $ultimoRegistro['ingreso'] !== null) {
                    $inbreak = $fere;
                    $sql = "UPDATE asis_empl SET inbreak = :inbreak WHERE idasem = :idasem";
                    $stmt = $connect->prepare($sql);
                    $stmt->bindParam(':inbreak', $inbreak);
                    $stmt->bindParam(':idasem', $idasem);
                } else {
                    mostrarError("No tienes registro de Ingreso hoy");
                    exit;
                }
                break;
            case 3: // Fin de break
                if ($ultimoRegistro && $ultimoRegistro['inbreak'] !== null) {
                    $fnbreak = $fere;
                    $sql = "UPDATE asis_empl SET fnbreak = :fnbreak WHERE idasem = :idasem";
                    $stmt = $connect->prepare($sql);
                    $stmt->bindParam(':fnbreak', $fnbreak);
                    $stmt->bindParam(':idasem', $idasem);
                } else {
                    mostrarError("No has registrado tu inicio de Break.");
                    exit;
                }
                break;
            case 4: // Salida
                if ($ultimoRegistro && $ultimoRegistro['fnbreak'] !== null) {
                    $salida = $fere;
                    $cierre = "Finalizado por Agente";
                    $sql = "UPDATE asis_empl SET salida = :salida, cierre = :cierre WHERE idasem = :idasem";
                    $stmt = $connect->prepare($sql);
                    $stmt->bindParam(':salida', $salida);
                    $stmt->bindParam(':cierre', $cierre);
                    $stmt->bindParam(':idasem', $idasem);
                } else {
                    mostrarError("No has marcado tu fin de Refrigerio.");
                    exit;
                }
                break;
            default:
                // Mostrar un mensaje de error si el ID de asistencia no es válido
                mostrarError("ID de asistencia no válido");
                exit;
        }

        try {
            if ($stmt->execute()) {
                // Éxito
                mostrarExito("La asistencia se registró correctamente");
            } else {
                // Error en la ejecución de la consulta
                mostrarError("Error en la ejecución de la consulta");
                // Imprimir información detallada sobre el error
                print_r($stmt->errorInfo());
            }
        } catch (PDOException $e) {
            // Capturar excepciones de PDO
            mostrarError('Error de PDO: ' . $e->getMessage());
        }
    } else {
        // Valor de $idasi es NULL, mostrar un mensaje de error
        mostrarError("El valor de idasi no puede ser NULL");
    }
}

function mostrarExito($mensaje) {
    echo '<script type="text/javascript">
            swal("¡Registrado!", "' . $mensaje . '", "success").then(function() {
                window.location = "../administrador_empleado/index.php";
            });
        </script>';
}

function mostrarError($mensaje) {
    echo '<script type="text/javascript">
            swal("Error!", "' . $mensaje . '", "error");
        </script>';
}
?>

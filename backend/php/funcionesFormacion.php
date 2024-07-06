<?php

require_once '../../backend/bd/ctconex.php';

date_default_timezone_set('America/Lima');

function ejecutarConsulta($sql, $params = []) {
    global $connect;
    try {
        $stmt = $connect->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => &$val) {
                $stmt->bindParam($key + 1, $val);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en la consulta SQL: " . $e->getMessage());
        return false; // Indica fallo
    }
}

function obtenerGruposPorFormador($dniFormador) {
    global $connect;
    try {
        $sql = "SELECT DISTINCT grupo FROM users_formacion WHERE dni_formador = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bindParam(1, $dniFormador, PDO::PARAM_STR);
        $stmt->execute();
        $grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($grupos)) {
            error_log("No se encontraron grupos para el formador con DNI: " . $dniFormador);
            return false;
        }
        return $grupos;
    } catch (PDOException $e) {
        error_log("Error al obtener grupos por formador: " . $e->getMessage());
        return false;
    }
}


function obtenerUsuariosPorGrupo($grupo) {
    $sql = "SELECT * FROM users_formacion WHERE grupo = ? AND estado = 'Activo' ORDER BY nombres ASC";
    return ejecutarConsulta($sql, [$grupo]);
}




function registrarAsistencia($idUsuario, $dniFormador, $grupo, $periodo, $fechaAsis, $asistencia, $obs, $contrato, $fechaContrato, $motivoDesercion, $ipUsuario = '') {
    global $connect;
    // Utilizar $fechaAsis para la columna fecha_asis
    $fechaFormateada = date('Y-m-d', strtotime($fechaAsis));
    $sql = "INSERT INTO asis_formacion (id_user, dni_formador, grupo, etapa, fecha_asis, asistencia, obs, contrato, fecha_contrato, desercion, user_reg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    try {
        $stmt = $connect->prepare($sql);
        $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(2, $dniFormador, PDO::PARAM_STR);
        $stmt->bindParam(3, $grupo, PDO::PARAM_STR);
        $stmt->bindParam(4, $periodo, PDO::PARAM_STR);
        $stmt->bindParam(5, $fechaFormateada, PDO::PARAM_STR);
        $stmt->bindParam(6, $asistencia, PDO::PARAM_STR);
        $stmt->bindParam(7, $obs, PDO::PARAM_STR);
        $stmt->bindParam(8, $contrato, PDO::PARAM_STR);
        $stmt->bindParam(9, $fechaContrato, PDO::PARAM_STR);
        $stmt->bindParam(10, $motivoDesercion, PDO::PARAM_STR);
        $stmt->bindParam(11, $ipUsuario, PDO::PARAM_STR);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log("Intentando insertar con fecha: " . $fechaAsis);

        error_log("Intentando insertar con fecha formateada: " . $fechaFormateada);
        return false;
    }
}

function verificarExistenciaAsistenciaHoy($dniFormador, $grupoSeleccionado, $fechaAsis) {
    global $connect;

    $sql = "SELECT COUNT(*) FROM asis_formacion WHERE dni_formador = ? AND grupo = ? AND DATE(fecha_asis) = ?";

    try {
        $stmt = $connect->prepare($sql);
        $stmt->execute([$dniFormador, $grupoSeleccionado, $fechaAsis]);
        $count = $stmt->fetchColumn();
        return $count > 0; // Retorna true si ya existe registro para esa fecha, false en caso contrario.
    } catch (Exception $e) {
        error_log('Error al verificar la existencia de asistencia: ' . $e->getMessage());
        return false; // Considera cambiar esto según cómo quieras manejar los errores.
    }
}

function obtenerDetallesAsistenciaPorFecha($dniFormador, $grupoSeleccionado, $fechaConsulta) {
    global $connect; // Asume que $connect es tu objeto de conexión PDO a la base de datos

    try {
        // Prepara la consulta SQL
        $sql = "SELECT af.fecha_asis, u.dni, u.nombres, af.dni_formador, u.nom_formador, u.programa, af.grupo, af.asistencia, af.obs, af.fecha_contrato, af.desercion
        FROM asis_formacion af
       INNER JOIN users_formacion u ON af.id_user = u.id_user and af.dni_formador = u.dni_formador
       WHERE af.dni_formador = :dniFormador AND af.grupo = :grupo AND DATE(af.fecha_asis)  = :fechaConsulta";
        
        // Prepara el statement
        $stmt = $connect->prepare($sql);
        
        // Vincula los parámetros
        $stmt->bindParam(':dniFormador', $dniFormador);
        $stmt->bindParam(':grupo', $grupoSeleccionado);
        $stmt->bindParam(':fechaConsulta', $fechaConsulta);
        
        // Ejecuta la consulta
        $stmt->execute();
        
        // Recupera los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $resultados;
    } catch (PDOException $e) {
        // Manejo del error
        error_log("Error al obtener detalles de asistencia por fecha: " . $e->getMessage());
        return false;
    }
}



?>
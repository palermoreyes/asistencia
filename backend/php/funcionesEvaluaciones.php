<?php

require_once '../../backend/bd/ctconex.php';  // Asegúrate de que la ruta sea correcta para la conexión a la base de datos

date_default_timezone_set('America/Lima');

// Función para ejecutar consultas SQL
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
        return false;
    }
}

// Función para obtener grupos asignados a un formador
function obtenerGruposPorFormador($dniFormador) {
    global $connect;
    try {
        $sql = "SELECT DISTINCT grupo FROM users_formacion WHERE dni_formador = ?";
        return ejecutarConsulta($sql, [$dniFormador]);
    } catch (PDOException $e) {
        error_log("Error al obtener grupos por formador: " . $e->getMessage());
        return false;
    }
}

// Función para obtener usuarios por grupo
function obtenerUsuariosPorGrupo($grupo) {
    global $connect;  // Asegúrate de que $connect es tu conexión PDO activa
    $sql = "SELECT * FROM users_formacion WHERE grupo = ? ORDER BY estado ASC, nombres ASC";

    try {
        $stmt = $connect->prepare($sql);
        $stmt->execute([$grupo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en la consulta SQL: " . $e->getMessage());
        return false;  // Retorna false en caso de error
    }
}

// Función para obtener usuarios por grupo incluyendo sus evaluaciones

function obtenerEvaluacionesPorGrupo($grupo) {
    global $connect; // Asegúrate de que $connect es tu conexión PDO activa
    $sql = "SELECT u.dni, u.nombres, u.grupo, u.estado AS user_estado, e.* 
            FROM users_formacion u
            LEFT JOIN eval_formacion e ON u.dni = e.dni_formador AND u.grupo = e.grupo
            WHERE u.grupo = ? 
            ORDER BY u.nombres ASC, u.estado ASC";

    try {
        $stmt = $connect->prepare($sql);
        $stmt->execute([$grupo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en la consulta SQL: " . $e->getMessage());
        return false;  // Retorna false en caso de error
    }
}



// Función para guardar las evaluaciones de los usuarios
function guardarEvaluaciones($idUsuario, $dniFormador, $grupo, $estado, $evaluaciones, $promedio, $estadoFinal) {
    global $connect;
    
    // Preparación de la consulta SQL para insertar o actualizar los registros
    $sql = "INSERT INTO eval_formacion 
            (id_user, dni_formador, grupo, estado, evaluacion1, evaluacion2, evaluacion3, evaluacion4, evaluacion5, evaluacion6, promedio, estado_final, fecha_eval_1, fecha_eval_2, fecha_eval_3, fecha_eval_4, fecha_eval_5, fecha_eval_6)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            evaluacion1 = VALUES(evaluacion1), fecha_eval_1 = VALUES(fecha_eval_1),
            evaluacion2 = VALUES(evaluacion2), fecha_eval_2 = VALUES(fecha_eval_2),
            evaluacion3 = VALUES(evaluacion3), fecha_eval_3 = VALUES(fecha_eval_3),
            evaluacion4 = VALUES(evaluacion4), fecha_eval_4 = VALUES(fecha_eval_4),
            evaluacion5 = VALUES(evaluacion5), fecha_eval_5 = VALUES(fecha_eval_5),
            evaluacion6 = VALUES(evaluacion6), fecha_eval_6 = VALUES(fecha_eval_6),
            promedio = VALUES(promedio), 
            estado_final = VALUES(estado_final)";

    // Preparar los valores de evaluaciones y fechas
    $evaluacionesPreparadas = [];
    $fechaParams = [];
    for ($i = 1; $i <= 6; $i++) {
        $eval = isset($evaluaciones[$i]) ? floatval($evaluaciones[$i]) : null;
        $evaluacionesPreparadas[] = $eval;
        $fechaParams[] = $eval !== null ? date('Y-m-d H:i:s') : null;
    }

    // Preparar todos los parámetros para la inserción
    $params = array_merge([$idUsuario, $dniFormador, $grupo, $estado], $evaluacionesPreparadas, [$promedio, $estadoFinal], $fechaParams);

    try {
        $stmt = $connect->prepare($sql);
        $stmt->execute($params);
        return true;
    } catch (PDOException $e) {
        error_log("Error al guardar evaluaciones: " . $e->getMessage());
        return false;
    }
}

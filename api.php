<?php
// API local de PAPOIS Gym. Recibe el formato JSON que ya usa index.html.
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

require_once __DIR__ . '/conexion.php';

// Debe coincidir con CONFIG.CLAVE en index.html. Es una barrera básica para uso local,
// NO una autenticación segura para publicar el sistema en Internet.
$claveEsperada = 'PAPOIS-605-cambia-esta-clave-2026';
$raw = file_get_contents('php://input');
$entrada = json_decode($raw, true);
if (!is_array($entrada)) {
    http_response_code(400);
    echo json_encode(['error' => 'El cuerpo de la solicitud no contiene JSON válido.']);
    exit;
}
if (!isset($entrada['clave']) || !hash_equals($claveEsperada, (string)$entrada['clave'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Clave de API incorrecta.']);
    exit;
}

$accion = $entrada['accion'] ?? '';
$tablasVacias = [
    'socios' => [], 'admins' => [], 'pagos' => [], 'asist' => [],
    'egresos' => [], 'reservas' => [], 'equipo' => [], 'banco' => [], 'emp' => []
];

try {
    if ($accion === 'load') {
        $stmt = $pdo->query('SELECT datos FROM estado_app WHERE id = 1 LIMIT 1');
        $fila = $stmt->fetch();
        if (!$fila) {
            echo json_encode($tablasVacias, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }
        $datos = json_decode($fila['datos'], true);
        if (!is_array($datos)) {
            http_response_code(500);
            echo json_encode(['error' => 'Los datos guardados no tienen formato JSON válido.']);
            exit;
        }
        // Asegura que todas las colecciones que espera la interfaz estén presentes.
        foreach ($tablasVacias as $k => $v) {
            if (!isset($datos[$k]) || !is_array($datos[$k])) $datos[$k] = $v;
        }
        echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    if ($accion === 'save') {
        if (!isset($entrada['datos']) || !is_array($entrada['datos'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Faltan los datos que se deben guardar.']);
            exit;
        }
        foreach ($tablasVacias as $k => $v) {
            if (!isset($entrada['datos'][$k]) || !is_array($entrada['datos'][$k])) {
                $entrada['datos'][$k] = $v;
            }
        }
        $json = json_encode($entrada['datos'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            http_response_code(400);
            echo json_encode(['error' => 'No se pudieron convertir los datos a JSON.']);
            exit;
        }
        $stmt = $pdo->prepare(
            'INSERT INTO estado_app (id, datos, actualizado_en) VALUES (1, :datos, NOW()) '
            . 'ON DUPLICATE KEY UPDATE datos = VALUES(datos), actualizado_en = NOW()'
        );
        $stmt->execute([':datos' => $json]);
        echo json_encode(['ok' => true, 'mensaje' => 'Datos guardados en MySQL.']);
        exit;
    }

    http_response_code(400);
    echo json_encode(['error' => 'Acción no reconocida. Usa load o save.']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al consultar o guardar los datos en MySQL. Revisa la tabla estado_app.']);
}

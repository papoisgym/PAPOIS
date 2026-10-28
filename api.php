<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
require_once __DIR__ . '/conexion.php';

/* Debe coincidir con CONFIG.CLAVE en index.html. Cambia ambas antes de publicar. */
$claveEsperada = 'PAPOIS-605-cambia-esta-clave-2026';
$entrada = json_decode(file_get_contents('php://input'), true);
if (!is_array($entrada)) { http_response_code(400); echo json_encode(['error'=>'JSON inválido']); exit; }
if (!isset($entrada['clave']) || !hash_equals($claveEsperada, (string)$entrada['clave'])) {
  http_response_code(403); echo json_encode(['error'=>'Clave de API incorrecta']); exit;
}
$colecciones = ['socios','admins','pagos','asist','egresos','reservas','equipo','banco','emp'];

function claveRegistro($coleccion, $fila, $indice) {
  $campos = [
    'socios'=>'id', 'admins'=>'u', 'pagos'=>'folio', 'asist'=>'id',
    'egresos'=>'id', 'reservas'=>'id', 'equipo'=>'id', 'banco'=>'id', 'emp'=>'id'
  ];
  $campo = $campos[$coleccion] ?? null;
  if ($campo && isset($fila[$campo]) && (string)$fila[$campo] !== '') return (string)$fila[$campo];
  if ($coleccion === 'asist') return hash('sha256', json_encode($fila, JSON_UNESCAPED_UNICODE).':'.$indice);
  if ($coleccion === 'egresos') return hash('sha256', json_encode($fila, JSON_UNESCAPED_UNICODE).':'.$indice);
  return 'fila-'.$indice;
}
try {
  $accion = $entrada['accion'] ?? '';
  if ($accion === 'load') {
    $datos = array_fill_keys($colecciones, []);
    $stmt = $pdo->query('SELECT coleccion, registro_id, datos FROM registros ORDER BY coleccion, actualizado_en, registro_id');
    $filas = $stmt->fetchAll();

    // Migración de una sola vez: si registros está vacío, intenta importar estado_app.
    // La tabla antigua se conserva; no se borra ni se modifica.
    if (!$filas) {
      try {
        $ant = $pdo->query('SELECT datos FROM estado_app WHERE id = 1 LIMIT 1')->fetch();
        $legado = $ant ? json_decode($ant['datos'], true) : null;
        if (is_array($legado)) {
          $pdo->beginTransaction();
          $up = $pdo->prepare('INSERT INTO registros (coleccion, registro_id, datos) VALUES (:c,:id,:d)
            ON DUPLICATE KEY UPDATE datos=VALUES(datos)');
          foreach ($colecciones as $c) {
            $lista = $legado[$c] ?? [];
            if (!is_array($lista)) continue;
            foreach (array_values($lista) as $i => $obj) {
              if (!is_array($obj)) continue;
              $id = claveRegistro($c, $obj, $i);
              $up->execute([':c'=>$c, ':id'=>$id, ':d'=>json_encode($obj, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
            }
          }
          $pdo->commit();
          $stmt = $pdo->query('SELECT coleccion, registro_id, datos FROM registros ORDER BY coleccion, actualizado_en, registro_id');
          $filas = $stmt->fetchAll();
        }
      } catch (Throwable $migracion) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        // Si no hay tabla antigua, continúa como base nueva.
      }
    }
    foreach ($filas as $r) {
      if (!isset($datos[$r['coleccion']])) continue;
      $obj = json_decode($r['datos'], true);
      if (is_array($obj)) $datos[$r['coleccion']][] = $obj;
    }
    echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); exit;
  }
  if ($accion === 'save') {
    if (!isset($entrada['datos']) || !is_array($entrada['datos'])) {
      http_response_code(400); echo json_encode(['error'=>'Faltan datos']); exit;
    }
    $pdo->beginTransaction();
    $up = $pdo->prepare('INSERT INTO registros (coleccion, registro_id, datos) VALUES (:c,:id,:d)
      ON DUPLICATE KEY UPDATE datos=VALUES(datos), actualizado_en=CURRENT_TIMESTAMP');
    foreach ($colecciones as $c) {
      $filas = $entrada['datos'][$c] ?? [];
      if (!is_array($filas)) $filas = [];
      $idsActuales = [];
      foreach (array_values($filas) as $i => $fila) {
        if (!is_array($fila)) continue;
        $id = claveRegistro($c, $fila, $i);
        $idsActuales[] = $id;
        $json = json_encode($fila, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) throw new RuntimeException('No se pudo convertir un registro a JSON.');
        $up->execute([':c'=>$c, ':id'=>$id, ':d'=>$json]);
      }
      // Sincroniza bajas: elimina filas que ya no están en la lista recibida.
      if ($idsActuales) {
        $marks = implode(',', array_fill(0, count($idsActuales), '?'));
        $del = $pdo->prepare("DELETE FROM registros WHERE coleccion = ? AND registro_id NOT IN ($marks)");
        $del->execute(array_merge([$c], $idsActuales));
      } else {
        $del = $pdo->prepare('DELETE FROM registros WHERE coleccion = ?');
        $del->execute([$c]);
      }
    }
    $pdo->commit();
    echo json_encode(['ok'=>true,'mensaje'=>'Registros guardados en filas separadas de MySQL.'], JSON_UNESCAPED_UNICODE); exit;
  }
  http_response_code(400); echo json_encode(['error'=>'Acción no reconocida']);
} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  http_response_code(500);
  echo json_encode(['error'=>'Error guardando/leyendo registros. Verifica que exista la tabla registros y los permisos MySQL.']);
}


<?php
// Conexión de PAPOIS Gym a MySQL en InfinityFree.

$host = 'sql306.infinityfree.com';
$dbName = 'if0_43289772_papoisgym';
$dbUser = 'if0_43289772';
$dbPass = 'ESCRIBE_AQUI_TU_CONTRASEÑA_MYSQL';
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$dbName};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'error' => 'No se pudo conectar a MySQL. Revisa la configuración de InfinityFree.'
    ]);
    exit;
}

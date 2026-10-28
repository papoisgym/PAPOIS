<?php
// Conexión local para PAPOIS Gym (XAMPP + MySQL/MariaDB).
// Configuración típica de XAMPP: usuario root y contraseña vacía.
// Si configuraste una contraseña para MySQL, escríbela en $dbPass.
$host = '127.0.0.1';
$dbName = 'papois_gym';
$dbUser = 'root';
$dbPass = '';
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
    echo json_encode(['error' => 'No se pudo conectar a MySQL. Revisa conexion.php y que MySQL esté iniciado.']);
    exit;
}

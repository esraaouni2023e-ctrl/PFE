<?php
$dsn = 'mysql:host=127.0.0.1;dbname=pfe;charset=utf8mb4';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $sql = 'DROP TABLE IF EXISTS `users`';
    $pdo->exec($sql);
    echo "Dropped users table if it existed\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

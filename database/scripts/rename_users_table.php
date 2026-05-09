<?php

$dsn = 'mysql:host=127.0.0.1;dbname=pfe;charset=utf8mb4';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $ts = date('Ymd_His');
    $new = "users_backup_{$ts}";
    $sql = "RENAME TABLE `users` TO `{$new}`";
    $pdo->exec($sql);
    echo "Renamed to {$new}\n";
} catch (PDOException $e) {
    echo "Error: ".$e->getMessage()."\n";
    exit(1);
}

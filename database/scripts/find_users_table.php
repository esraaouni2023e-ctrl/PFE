<?php
$dsn = 'mysql:host=127.0.0.1;charset=utf8mb4';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->prepare("SELECT TABLE_SCHEMA, TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_NAME = 'users'");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows)) {
        echo "No 'users' table found in any schema\n";
    } else {
        foreach ($rows as $r) {
            echo "Schema: {$r['TABLE_SCHEMA']}, Name: {$r['TABLE_NAME']}, Type: {$r['TABLE_TYPE']}\n";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

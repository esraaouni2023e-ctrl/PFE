<?php
$dsn = 'mysql:host=127.0.0.1;dbname=pfe;charset=utf8mb4';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query('SHOW TABLES');
    $rows = $stmt->fetchAll(PDO::FETCH_NUM);
    if (empty($rows)) {
        echo "No tables found\n";
    } else {
        foreach ($rows as $r) {
            echo $r[0] . "\n";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

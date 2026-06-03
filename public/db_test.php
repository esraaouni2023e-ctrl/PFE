<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Connection Diagnostic</h1>";

$host = getenv('DB_HOST');
$port = getenv('DB_PORT') ?: '3306';
$database = getenv('DB_DATABASE');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');

echo "<p>Testing connection to: <strong>$host:$port</strong> (Database: <strong>$database</strong>, User: <strong>$username</strong>)</p>";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5, // 5 seconds timeout
    ];
    
    $start = microtime(true);
    $pdo = new PDO($dsn, $username, $password, $options);
    $end = microtime(true);
    
    echo "<p style='color: green; font-weight: bold;'>✅ Connection Successful! (Took " . round(($end - $start) * 1000, 2) . " ms)</p>";
    
    // Check tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<h3>Tables in Database:</h3>";
    if (empty($tables)) {
        echo "<p>No tables found in database.</p>";
    } else {
        echo "<ul>";
        foreach ($tables as $table) {
            // Count rows
            $countStmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
            $count = $countStmt->fetchColumn();
            echo "<li>$table: <strong>$count rows</strong></li>";
        }
        echo "</ul>";
    }
    
} catch (\Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ Connection Failed!</p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}

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
    
    // Check Processlist
    echo "<h3>Active Database Processes (SHOW PROCESSLIST):</h3>";
    $processStmt = $pdo->query("SHOW PROCESSLIST");
    $processes = $processStmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($processes)) {
        echo "<p>No active processes found.</p>";
    } else {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>Id</th><th>User</th><th>Host</th><th>db</th><th>Command</th><th>Time</th><th>State</th><th>Info</th></tr>";
        foreach ($processes as $p) {
            echo "<tr>";
            echo "<td>{$p['Id']}</td>";
            echo "<td>{$p['User']}</td>";
            echo "<td>{$p['Host']}</td>";
            echo "<td>{$p['db']}</td>";
            echo "<td>{$p['Command']}</td>";
            echo "<td>{$p['Time']}</td>";
            echo "<td>{$p['State']}</td>";
            echo "<td>" . htmlspecialchars($p['Info'] ?? '') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check Git Commit
    echo "<h3>Current Running Commit (git log -1):</h3>";
    $gitLog = shell_exec('git log -1 2>&1');
    echo "<pre>" . htmlspecialchars($gitLog ?: 'git command not available') . "</pre>";
    
    // Check Container Startup Logs
    echo "<h3>Container Startup Logs (/storage/logs/startup.log):</h3>";
    $logPath = __DIR__ . '/../storage/logs/startup.log';
    if (file_exists($logPath)) {
        echo "<pre>" . htmlspecialchars(file_get_contents($logPath)) . "</pre>";
    } else {
        echo "<p style='color: orange;'>Startup log file not found at: " . htmlspecialchars($logPath) . "</p>";
    }
    
} catch (\Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ Connection Failed!</p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}

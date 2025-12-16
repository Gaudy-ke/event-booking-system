<?php
// Debug script for user@endpoint method

$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

$databaseUrl = $_ENV['DATABASE_URL'] ?? getenv('DATABASE_URL');

if ($databaseUrl) {
    $url = parse_url($databaseUrl);
    
    $host = $url['host'];
    $port = $url['port'] ?? 5432;
    $dbname = ltrim($url['path'], '/');
    $user = $url['user'];
    $password = $url['pass'];

    // Extract endpoint ID from host (first part before dot)
    $endpointId = explode('.', $host)[0];
    
    // Append endpoint ID to user
    $userWithEndpoint = $user . "@" . $endpointId;
    
    echo "Host: $host<br>";
    echo "Endpoint ID: $endpointId<br>";
    echo "Original User: $user<br>";
    echo "New User: $userWithEndpoint<br>";

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require";
    
    try {
        echo "Attempting connection with user@endpoint...<br>";
        $conn = new PDO($dsn, $userWithEndpoint, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<strong style='color:green'>Connection Successful!</strong>";
    } catch(PDOException $e) {
        echo "<strong style='color:red'>Connection Failed:</strong> " . $e->getMessage();
    }
} else {
    echo "DATABASE_URL not found.";
}
?>

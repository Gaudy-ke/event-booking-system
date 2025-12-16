<?php
// exact logic from config/database.php but flattened
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
$url = parse_url($databaseUrl);
$host = $url['host'];
$port = $url['port'] ?? 5432;
$dbname = ltrim($url['path'], '/');
$user = $url['user'];
$password = $url['pass'];

$endpointId = explode('.', $host)[0];
if (strpos($user, $endpointId) === false) {
    $user .= "@" . $endpointId;
}

$dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require";

if (isset($url['query'])) {
    parse_str($url['query'], $params);
    foreach($params as $key => $value) {
        if ($key === 'options') {
             $dsn .= ";options=" . $value;
        }
    }
}

echo "User: " . $user . "\n";
echo "DSN: " . $dsn . "\n";

try {
    $conn = new PDO($dsn, $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection Successful!\n";
} catch(PDOException $e) {
    echo "Connection 1 (Pooler ID) Failed: " . $e->getMessage() . "\n";
}

echo "<br>--- Attempt 2: Stripping -pooler from ID ---<br>\n";
$endpointId2 = str_replace('-pooler', '', $endpointId);
$user2 = $url['user'];
if (strpos($user2, $endpointId2) === false) {
    $user2 .= "@" . $endpointId2;
}
echo "User 2: " . $user2 . "\n";

try {
    $conn = new PDO($dsn, $user2, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection 2 Successful!\n";
} catch(PDOException $e) {
    echo "Connection 2 Failed: " . $e->getMessage() . "\n";
}
?>

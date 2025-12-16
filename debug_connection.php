<?php
// Load parsing logic from config/database.php (copied here to debug without modifying original yet)

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

echo "Raw Database URL length: " . strlen($databaseUrl) . "<br>\n";

if ($databaseUrl) {
    $url = parse_url($databaseUrl);
    
    echo "Host: " . ($url['host'] ?? 'Not set') . "<br>\n";
    echo "Port: " . ($url['port'] ?? 'Not set') . "<br>\n";
    echo "User: " . ($url['user'] ?? 'Not set') . "<br>\n";
    echo "Pass: " . (isset($url['pass']) ? '****' : 'Not set') . "<br>\n";
    echo "Path (DB): " . ($url['path'] ?? 'Not set') . "<br>\n";
    echo "Query: " . ($url['query'] ?? 'Not set') . "<br>\n";
    
    $host = $url['host'];
    $port = $url['port'] ?? 5432;
    $dbname = ltrim($url['path'], '/');
    $user = $url['user'];
    $password = $url['pass'];

    // Original DSN construction
    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require";
    echo "Constructed DSN: $dsn<br>\n";
    
    // Attempt Connection
    try {
        $conn = new PDO($dsn, $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<strong style='color:green'>Connection Successful!</strong>";
    } catch(PDOException $e) {
        echo "<strong style='color:red'>Connection Failed:</strong> " . $e->getMessage();
        
        // Try appending query params to options if present
        if (isset($url['query'])) {
             echo "<br>Trying alternative DSN with endpoint options...<br>";
             // Parse query string
             parse_str($url['query'], $params);
             
             // Construct new DSN including options
             // Note: PDO PGSQL supports 'options' in DSN or as driver options? 
             // Actually, usually endpoint ID is passed as part of the password or user in some pooled setups, 
             // OR as 'options=endpoint=...' in the connection string.
             // Let's try appending the query string to the DSN options if valid.
             
             $dsnWithOptions = $dsn;
             foreach($params as $k => $v) {
                 if($k !== 'sslmode') { // sslmode is already hardcoded
                     $dsnWithOptions .= ";$k=$v";
                 }
             }
             echo "Alternative DSN: $dsnWithOptions<br>\n";
             
             try {
                $conn2 = new PDO($dsnWithOptions, $user, $password);
                echo "<strong style='color:green'>Alternative Connection Successful!</strong>";
             } catch (PDOException $e2) {
                 echo "<strong style='color:red'>Alternative Failed:</strong> " . $e2->getMessage();
             }
        }
    }
} else {
    echo "DATABASE_URL not found.";
}
?>

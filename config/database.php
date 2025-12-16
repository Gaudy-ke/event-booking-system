<?php
class Database {
    public $conn;

    public function __construct() {
        try {
            // Load .env file
            $envFile = __DIR__ . '/../.env';
            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos($line, '#') === 0) continue; // Skip comments
                    if (strpos($line, '=') !== false) {
                        list($key, $value) = explode('=', $line, 2);
                        $_ENV[trim($key)] = trim($value);
                    }
                }
            }

            // Get connection string from environment
            $databaseUrl = $_ENV['DATABASE_URL'] ?? getenv('DATABASE_URL');
            
            if (!$databaseUrl) {
                throw new Exception("DATABASE_URL not found in .env file");
            }

            // Parse the connection string
            $url = parse_url($databaseUrl);
            $host = $url['host'];
            $port = $url['port'] ?? 5432;
            $dbname = ltrim($url['path'], '/');
            $user = $url['user'];
            $password = $url['pass'];

            $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require";
            
            // Force-add endpoint ID to DSN options
            // This is the most reliable way for Neon DB with PDO
            $endpointId = explode('.', $host)[0];
            $dsn .= ";options=endpoint=" . $endpointId;

            $this->conn = new PDO($dsn, $user, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        } catch(Exception $e) {
            die("Configuration error: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Create session
session_start();
?>
<?php
// ============================================================
//  config/database.php — PDO connection (Amazon RDS compatible)
// ============================================================

// Load .env values
function loadEnv(string $path): void {
    if (!file_exists($path)) return;
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (!str_contains($line, '=')) continue;
        [$key, $val] = array_map('trim', explode('=', $line, 2));
        $val = trim($val, '"\'');
        if (getenv($key) === false && !array_key_exists($key, $_ENV)) {
            putenv("$key=$val");
            $_ENV[$key] = $val;
        }
    }
}

loadEnv(__DIR__ . '/../.env');

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'aws_ecommerce');
define('DB_PORT', getenv('DB_PORT') ?: '3306');

// Singleton PDO connection
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            DB_HOST, DB_PORT, DB_NAME
        );
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            $isApi = str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/');
            if ($isApi) {
                die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
            }
            $errorMsg = htmlspecialchars($e->getMessage());
            $host = htmlspecialchars(DB_HOST);
            $port = htmlspecialchars(DB_PORT);
            $user = htmlspecialchars(DB_USER);
            $db   = htmlspecialchars(DB_NAME);
            echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Error — ShopNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #e2e8f0; font-family: system-ui, -apple-system, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card-error { background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 32px; max-width: 600px; width: 100%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5); }
        .badge-err { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; padding: 4px 10px; font-size: 0.85rem; }
        code { background: #0f172a; color: #38bdf8; padding: 3px 6px; border-radius: 4px; font-size: 0.85rem; }
        pre { background: #0f172a; color: #f87171; padding: 12px; border-radius: 8px; font-size: 0.82rem; overflow-x: auto; border: 1px solid #334155; }
    </style>
</head>
<body>
    <div class="card-error">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="m-0 text-white">🛍️ ShopNest</h4>
            <span class="badge-err">Database Error 500</span>
        </div>
        <h5 class="text-danger mb-2">Unable to connect to MySQL database</h5>
        <p class="text-secondary small mb-3">Target host: <code>$host:$port</code> | Database: <code>$db</code> | User: <code>$user</code></p>
        <pre>$errorMsg</pre>
        <hr class="border-secondary my-3">
        <h6 class="text-light">How to fix this:</h6>
        <ul class="text-secondary small ps-3 mb-3">
            <li><strong>Docker Compose:</strong> Run <code>docker compose up -d</code> to start both the app and the MySQL database container.</li>
            <li><strong>Standalone Docker:</strong> Ensure MySQL is running on a shared network and pass <code>-e DB_HOST=&lt;mysql-container-name&gt;</code>.</li>
            <li><strong>Local XAMPP:</strong> Make sure MySQL is started in your XAMPP Control Panel.</li>
        </ul>
    </div>
</body>
</html>
HTML;
            exit;
        }
    }
    return $pdo;
}

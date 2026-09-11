<?php
$db_host = getenv('DB_HOST') ?: 'db';
$db_name = getenv('DB_NAME') ?: 'mydatabase';
$db_user = getenv('DB_USER') ?: 'lamp';
$db_pass = getenv('DB_PASS') ?: 'lamp';

$db_connected = false;
$db_error = '';
$server_info = '';

try {
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 3,
    ]);
    $db_connected = true;
    $server_info = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
} catch (PDOException $e) {
    $db_connected = false;
    $db_error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAMP Docker Environment</title>
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-color: #f8fafc;
            --text-muted: #94a3b8;
            --accent-blue: #38bdf8;
            --success: #22c55e;
            --error: #ef4444;
            --border-color: #334155;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 800px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .header h1 {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .header p {
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-badge.online {
            background-color: rgba(34, 197, 94, 0.1);
            color: var(--success);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .status-badge.offline {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--error);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .online .dot { background-color: var(--success); }
        .offline .dot { background-color: var(--error); }

        .info-list {
            list-style: none;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.95rem;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label { color: var(--text-muted); }
        .info-value { font-weight: 500; color: var(--text-color); }

        .actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            flex: 1;
            min-width: 200px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 0.75rem 1.25rem;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--accent-blue);
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        .error-msg {
            margin-top: 0.75rem;
            padding: 0.5rem;
            background-color: rgba(239, 68, 68, 0.1);
            border-radius: 0.375rem;
            color: var(--error);
            font-size: 0.85rem;
            word-break: break-all;
        }

        footer {
            text-align: center;
            margin-top: 2rem;
            color: var(--text-muted);
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🚀 LAMP Stack Environment</h1>
        <p>Docker Local Development Environment Status</p>
    </div>

    <div class="grid">
        <!-- Web Server Status -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">Web Server</span>
                <span class="status-badge online">
                    <span class="dot"></span> Running
                </span>
            </div>
            <ul class="info-list">
                <li class="info-item">
                    <span class="info-label">Web Server</span>
                    <span class="info-value"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Apache'; ?></span>
                </li>
                <li class="info-item">
                    <span class="info-label">PHP Version</span>
                    <span class="info-value"><?php echo phpversion(); ?></span>
                </li>
                <li class="info-item">
                    <span class="info-label">Document Root</span>
                    <span class="info-value">/var/www/localhost/htdocs</span>
                </li>
            </ul>
        </div>

        <!-- Database Status -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">Database</span>
                <?php if ($db_connected): ?>
                    <span class="status-badge online">
                        <span class="dot"></span> Connected
                    </span>
                <?php else: ?>
                    <span class="status-badge offline">
                        <span class="dot"></span> Disconnected
                    </span>
                <?php endif; ?>
            </div>
            <ul class="info-list">
                <li class="info-item">
                    <span class="info-label">Engine</span>
                    <span class="info-value">MariaDB</span>
                </li>
                <li class="info-item">
                    <span class="info-label">Host</span>
                    <span class="info-value"><?php echo $db_host; ?>:3306</span>
                </li>
                <li class="info-item">
                    <span class="info-label">Database</span>
                    <span class="info-value"><?php echo $db_name; ?></span>
                </li>
            </ul>
            <?php if (!$db_connected): ?>
                <div class="error-msg">
                    <?php echo htmlspecialchars($db_error); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="actions">
        <a href="http://localhost:8080" target="_blank" class="btn">
            Open phpMyAdmin &rarr;
        </a>
        <a href="info.php" class="btn" target="_blank">
            View phpinfo() &rarr;
        </a>
    </div>

    <footer>
        <p>Managed with Docker Compose & Alpine Linux</p>
    </footer>
</div>

</body>
</html>
<?php

$localConfigFiles = [
    __DIR__ . '/database.local.php',
    '/etc/secrets/database.local.php',
];

$localConfig = [];
foreach ($localConfigFiles as $localConfigFile) {
    if (file_exists($localConfigFile)) {
        $localConfig = require $localConfigFile;
        break;
    }
}

$configValue = static function (string $key, ?string $default = null) use ($localConfig): string {
    $environmentValue = getenv($key);
    $value = $environmentValue !== false ? $environmentValue : ($localConfig[$key] ?? $default);

    if ($value === null || $value === '') {
        throw new RuntimeException("Missing database configuration value: {$key}");
    }

    return (string) $value;
};

define('DB_HOST', $configValue('DB_HOST'));
define('DB_PORT', $configValue('DB_PORT', '5432'));
define('DB_NAME', $configValue('DB_NAME', 'postgres'));
define('DB_USER', $configValue('DB_USER'));
define('DB_PASS', $configValue('DB_PASS'));
define('DB_SSLMODE', $configValue('DB_SSLMODE', 'require'));

// Derive base URL from script location so the app works at any sub-path
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));

function getDBConnection(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s;sslmode=%s',
            DB_HOST,
            DB_PORT,
            DB_NAME,
            DB_SSLMODE
        );
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log('FuelGuard DB Error: ' . $e->getMessage());
            die('<div style="font-family:sans-serif;padding:2rem;color:#991b1b">
                    <h2>Database connection failed.</h2>
                    <p>Please check your Supabase settings in <code>config/database.local.php</code>.</p>
                 </div>');
        }
    }
    return $pdo;
}
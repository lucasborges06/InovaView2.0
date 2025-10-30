<?php
// db_init.php - inicializa banco SQLite e assegura configuração inicial
if (!is_dir(__DIR__ . '/data')) mkdir(__DIR__ . '/data', 0755, true);
$dbfile = __DIR__ . '/data/inovaview.db';
$init = !file_exists($dbfile);
$db = new PDO('sqlite:' . $dbfile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if ($init) {
    $db->exec("CREATE TABLE uploads (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        filename TEXT NOT NULL,
        title TEXT,
        description TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );");
    $db->exec("CREATE TABLE messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        email TEXT,
        message TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );");
}
// Ensure config file exists and default admin hash set on first run
$cfgFile = __DIR__ . '/config_local.php';
if (!file_exists($cfgFile)) {
    $default = [
        'site_name' => 'InovaView',
        'admin_user' => 'admin',
        // plain default senha: admin123 (será hash)
    ];
    // compute hash if possible
    $default['admin_hash'] = password_hash('admin123', PASSWORD_DEFAULT);
    file_put_contents($cfgFile, '<?php return ' . var_export($default, true) . '; ?>');
}
return $db;
?>

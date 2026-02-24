
<?php
define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// // define('DB_NAME', 'rooms');
define('DB_USER', 'u267490263_roomlinks');
define('DB_PASS', 'Y32^zdXEi]8');
define('DB_NAME', 'u267490263_roomlinks');
// define('DB_USER', 'u267490263_roomlinkslive');
// define('DB_PASS', 'a=z9S5gnDg>9');
// define('DB_NAME', 'u267490263_roomlinkslive');
// Add PDO connection for models
function getPDO() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

function getDatabaseConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>

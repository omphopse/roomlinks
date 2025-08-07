<?php
// helpers/PersistentLogin.php
require_once __DIR__ . '/../config/database.php';

class PersistentLogin {
    private $cookieName = 'roomlinks_auth';
    private $cookieExpireDays = 30;
    private $cookiePath = '/';
    private $cookieSecure = false; // Set true if using HTTPS
    private $cookieHttpOnly = true;
    private $db;

    public function __construct($pdo = null) {
        if ($pdo !== null) {
            $this->db = $pdo;
        } else {
            $this->db = getPDO();
        }
    }

    public function setToken($userId) {
        $token = bin2hex(random_bytes(32));
        $expiresUnix = time() + 60 * 60 * 24 * $this->cookieExpireDays;
        $expires = date('Y-m-d H:i:s', $expiresUnix);
        // Store in DB
        $stmt = $this->db->prepare('INSERT INTO user_tokens (user_id, token, expires_at) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $token, $expires]);
        // Set cookie (must be before any output!)
        $cookieOptions = [
            'expires' => $expiresUnix,
            'path' => $this->cookiePath,
            'secure' => $this->cookieSecure,
            'httponly' => $this->cookieHttpOnly,
            'samesite' => 'Lax',
        ];
        $headersSent = headers_sent($file, $line);
        $cookieSet = false;
        $headerWarning = null;
        if ($headersSent) {
            $headerWarning = "WARNING: Headers already sent in $file on line $line. setcookie will not work!";
        } else {
            $cookieSet = setcookie($this->cookieName, $token, $cookieOptions);
        }
        $debugMsg = [
            'msg' => $cookieSet
                ? 'PersistentLogin: Cookie set for user_id ' . $userId . ' token ' . $token
                : 'PersistentLogin: Failed to set cookie!',
            'setcookie_call' => [
                'name' => $this->cookieName,
                'value' => $token,
                'options' => $cookieOptions
            ],
            'server_vars' => [
                'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? null,
                'HTTPS' => $_SERVER['HTTPS'] ?? null,
                'SERVER_PORT' => $_SERVER['SERVER_PORT'] ?? null,
                'REQUEST_SCHEME' => $_SERVER['REQUEST_SCHEME'] ?? null,
                'HTTP_X_REQUESTED_WITH' => $_SERVER['HTTP_X_REQUESTED_WITH'] ?? null
            ],
            'cookie_set_immediately' => isset($_COOKIE[$this->cookieName]) ? $_COOKIE[$this->cookieName] : null,
            'headers_sent' => $headersSent,
            'headers_sent_file' => $headersSent ? $file : null,
            'headers_sent_line' => $headersSent ? $line : null,
            'header_warning' => $headerWarning
        ];
        error_log('PersistentLogin Debug: ' . json_encode($debugMsg));
        // For non-AJAX requests, print debug to browser console
        if (php_sapi_name() !== 'cli' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            echo "<script>console.log('" . addslashes(json_encode($debugMsg)) . "');</script>";
        }
        // For AJAX, return debugMsg
        return $debugMsg;
    }

    public function checkToken() {
        if (isset($_SESSION['user_id'])) return;
        if (empty($_COOKIE[$this->cookieName])) {
            error_log('PersistentLogin: No cookie found for ' . $this->cookieName);
            return;
        }
        $token = $_COOKIE[$this->cookieName];
        $stmt = $this->db->prepare('SELECT user_id FROM user_tokens WHERE token = ? AND expires_at > NOW()');
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['user_id'])) {
            // Set session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['logged_in'] = true;
            error_log('PersistentLogin: Session restored for user_id ' . $row['user_id']);
            // Optionally, refresh token expiry
            $this->refreshToken($token);
        } else {
            error_log('PersistentLogin: Invalid or expired token. Clearing cookie.');
            $this->clearToken();
        }
    }

    public function clearToken() {
        if (!empty($_COOKIE[$this->cookieName])) {
            $token = $_COOKIE[$this->cookieName];
            $stmt = $this->db->prepare('DELETE FROM user_tokens WHERE token = ?');
            $stmt->execute([$token]);
            setcookie($this->cookieName, '', time() - 3600, $this->cookiePath);
        }
    }

    private function refreshToken($token) {
        $expires = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * $this->cookieExpireDays);
        $stmt = $this->db->prepare('UPDATE user_tokens SET expires_at = ? WHERE token = ?');
        $stmt->execute([$expires, $token]);
        setcookie($this->cookieName, $token, [
            'expires' => time() + 60 * 60 * 24 * $this->cookieExpireDays,
            'path' => $this->cookiePath,
            'secure' => $this->cookieSecure,
            'httponly' => $this->cookieHttpOnly,
            'samesite' => 'Lax',
        ]);
    }
}

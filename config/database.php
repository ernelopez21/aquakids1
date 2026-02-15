<?php
// config/database.php - Versión segura (puede incluirse varias veces sin error)
if (!function_exists('getPDO')) {
    function getPDO(): PDO {
        static $pdo = null;

        if ($pdo === null) {
            try {
                $host   = 'localhost';
                $dbname = 'aquakids';
                $user   = 'root';
                $pass   = '';

                $pdo = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                die('Error de conexión: ' . htmlspecialchars($e->getMessage()));
            }
        }
        return $pdo;
    }
}
?>
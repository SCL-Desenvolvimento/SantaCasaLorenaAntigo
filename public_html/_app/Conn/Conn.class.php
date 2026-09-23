<?php
class Conn {
    private static ?PDO $connection = null;
    public static function getConn(): PDO {
        if (!self::$connection) {
            if (!HOST || !USER || !DBSA) throw new RuntimeException('Database environment is not configured.');
            self::$connection = new PDO('mysql:host=' . HOST . ';dbname=' . DBSA . ';charset=utf8mb4', USER, PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]);
        }
        return self::$connection;
    }
}

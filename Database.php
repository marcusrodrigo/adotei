<?php
class Database {
    private static $instance = null;
    private function __construct() {}

    public static function getConnection() {
        if (self::$instance === null) {
            $config = parse_ini_file('config.ini');
            self::$instance = new PDO("sqlite:" . $config['database']['path']);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}
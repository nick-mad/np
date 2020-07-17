<?php

namespace App\Core;

use PDO;
use PDOException;

class Connection
{
    protected static $instance;

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            try {
                self::$instance = new PDO('mysql:host=' . DB_HOST. ';port=3306;dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_LAZY);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $error) {
                exit('Error: ' . $error->getMessage());
            }
        }

        return self::$instance;
    }
}

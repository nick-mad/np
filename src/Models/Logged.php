<?php

namespace App\Models;

use App\Core\Connection;

class Logged
{
    private $connection;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    public function create($data)
    {
        $query = 'INSERT INTO logged 
                  SET 
                    date_from = ?, 
                    date_to = ?,
                    date_diff = ?,
                    time_process = ?,
                    ip = ?';
        $insertProduct = $this->connection->prepare($query);
        $insertProduct->execute(
            [
                $data['dateFrom'],
                $data['dateTo'],
                $data['dateDiff'],
                $data['timeProcess'],
                $data['ip'],
            ]
        );

        return $this->connection->lastInsertId();
    }

    public function updateTimeProcess($id, $timeProcess)
    {
        $query = 'UPDATE logged 
                  SET time_process = ?
                  WHERE id = ?';
        $insertProduct = $this->connection->prepare($query);
        $insertProduct->execute(
            [
                $timeProcess,
                $id,
            ]
        );
    }
}

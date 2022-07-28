<?php

namespace JLA;

use PDO;

class DB
{


    public static function getConnection()
    {
        static $connection;

        if($connection === null) {
            $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8';
            $connection = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

            // Throw an Exception when an error occurs
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $connection;
    }

    protected function execute($sql, $data = array(), $reset = true)
    {
        $connection = $this->getConnection();
		$this->statement = $connection->prepare($sql);
		$result = $this->statement->execute($data);
		if ($reset) {
			//$this->reset();
		}
		return $result;
	}
}

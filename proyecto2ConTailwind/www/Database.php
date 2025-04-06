<?php

class Database{
    private $pdo;

    public function __construct($config, $username, $password,$dbType = "mysql"){
        $dsn = $dbType.":". http_build_query($config, "", ";");
        $this->pdo = new PDO(
            $dsn,
            $username,
            $password,
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }

    public function query($query){
        $preparedQueryStatement = $this->pdo->prepare($query);
        $preparedQueryStatement->execute();

        return $preparedQueryStatement;
    }
}
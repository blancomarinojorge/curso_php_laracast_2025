<?php

class Database2
{

    private $pdo;
    private $statement;

    public function __construct($user, $password, $config, $dbType = "mysql")
    {
        $conection = new PDO(
            $dbType.":".http_build_query($config, "", ";"),
            $user,
            $password,
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        $this->pdo = $conection;
    }

    public function query($query, $params = []){
        $this->statement = $this->pdo->prepare($query);
        $this->statement->execute($params);
        return $this;
    }

    public function fetchOrAbort(){
        if ($this->statement==null){
            return null;
        }

        $data = $this->statement->fetch();

        if (!$data){
            abort();
        }

        return $data;
    }
    public function fetch(){
        if ($this->statement==null){
            return null;
        }

        return  $this->statement->fetch();
    }

    public function fetchAll(){
        if ($this->statement==null){
            return null;
        }

        return  $this->statement->fetchAll();
    }
}
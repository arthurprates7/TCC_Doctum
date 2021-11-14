<?php

    namespace src\config;

    abstract class Connection{

        protected $pdo = NULL;

        public function __construct(){

            $host   = "127.0.0.1";
            $port   = "3306";
            $user   = "root";
            $pass   = "";
            $dbname = "economia";

            $dsn = "mysql:host={$host};dbname={$dbname};port={$port}";

            $this->pdo = new \PDO($dsn, $user, $pass);
            $this->pdo->setAttribute(
                \PDO::ATTR_ERRMODE,
                \PDO::ERRMODE_EXCEPTION
            );            

        }

    }
<?php
class Database {
    private $host = "db";              
    private $dbname = "almoxif";      
    private $username = "almox";       
    private $password = "almox123";    
    private static $conn = null;       

    public function connect() {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_PERSISTENT => true,         
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                    ]
                );
            } catch (PDOException $e) {
                error_log("Erro de conexão: " . $e->getMessage());
                die("Erro ao conectar ao banco de dados.");
            }
        }
        return self::$conn;
    }
}
?>

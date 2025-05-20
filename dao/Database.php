<?php
class Database {
    private $host = "db";              // Nome do serviço MySQL no docker-compose
    private $dbname = "almoxif";       // Nome do banco definido no docker-compose
    private $username = "almox";       // Usuário definido no docker-compose
    private $password = "almox123";    // Senha definida no docker-compose
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
        return $this->conn;
    }
}
?>

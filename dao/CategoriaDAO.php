<?php
require_once "Database.php";
require_once "../classes/Categoria.php";

class CategoriaDAO {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function listarTodos() {
        $sql = "SELECT * FROM Categoria";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM Categoria WHERE id_categoria = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function criarCategoria($nome) {
        $sql = "INSERT INTO Categoria (nome) VALUES (:nome)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nome", $nome);
        return $stmt->execute();
    }
}
?>

<?php
require_once "Database.php";
require_once "../classes/Categoria.php";

class CategoriaDAO {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function listarTodos() {
        $sql = "SELECT * FROM categoria";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM categoria WHERE id_categoria = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function criarCategoria($nome) {
        $sql = "INSERT INTO categoria (nome) VALUES (:nome)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nome", $nome);
        return $stmt->execute();
    }

    public function atualizarNome($id, $novoNome) {
    $sql = "UPDATE categoria SET nome = :nome WHERE id_categoria = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":nome", $novoNome);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
}
}
?>

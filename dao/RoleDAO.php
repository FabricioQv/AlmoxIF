<?php
require_once "Database.php";
require_once "../classes/Role.php";

class RoleDAO {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function listarTodos() {
        try {
            $sql = "SELECT * FROM role"; // Certifique-se de que 'role' está em minúsculas
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $roles;
        } catch (PDOException $e) {
            die("Erro ao buscar funções: " . $e->getMessage());
        }
    }
    
    public function buscarPorId($id) {
        $sql = "SELECT * FROM role WHERE id_role = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

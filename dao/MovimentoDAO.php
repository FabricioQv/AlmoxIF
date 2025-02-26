<?php
require_once "Database.php";
require_once "../classes/Movimento.php";

class MovimentoDAO {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function registrarMovimento($movimento) {
        $sql = "INSERT INTO Movimento (fk_Item_id_item, fk_Usuario_id_usuario, dataSaida, dataEntrada, quantidadeMovimentada) 
                VALUES (:item, :usuario, :dataSaida, :dataEntrada, :quantidade)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":item", $movimento->getItemId());
        $stmt->bindParam(":usuario", $movimento->getUsuarioId());
        $stmt->bindParam(":dataSaida", $movimento->getDataSaida());
        $stmt->bindParam(":dataEntrada", $movimento->getDataEntrada());
        $stmt->bindParam(":quantidade", $movimento->getQuantidadeMovimentada());
        return $stmt->execute();
    }

    public function listarTodos() {
        $sql = "SELECT * FROM Movimento";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

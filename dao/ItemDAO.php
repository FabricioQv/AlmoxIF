<?php
require_once "Database.php";

class ItemDAO {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function verificarCodigoExistente($codigo) {
        $sql = "SELECT COUNT(*) FROM item WHERE codigo = :codigo";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":codigo", $codigo);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function cadastrarItem($nome, $codigo, $quantidade, $validade, $categoria, $imagem) {
        $sql = "INSERT INTO item (nome, codigo, quantidadeEstoque, dataValidade, fk_Categoria_id_categoria, imagem)
                VALUES (:nome, :codigo, :quantidade, :validade, :categoria, :imagem)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":codigo", $codigo);
        $stmt->bindParam(":quantidade", $quantidade);
        $stmt->bindParam(":validade", $validade);
        $stmt->bindParam(":categoria", $categoria);
        $stmt->bindParam(":imagem", $imagem);
    
        return $stmt->execute();
    }
    

    public function listarItens($termoBusca = "") {
        $sql = "SELECT i.*, c.nome AS categoria_nome 
                FROM item i
                INNER JOIN categoria c ON i.fk_Categoria_id_categoria = c.id_categoria";
    
        // Se o usuário digitou algo na barra de busca, adicionamos um filtro
        if (!empty($termoBusca)) {
            $sql .= " WHERE i.nome LIKE :busca";
        }
    
        $stmt = $this->conn->prepare($sql);
    
        if (!empty($termoBusca)) {
            $termoBusca = "%$termoBusca%";
            $stmt->bindParam(":busca", $termoBusca);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>

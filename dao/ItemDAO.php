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

    public function cadastrarItem($nome, $codigo, $categoria, $estoqueCritico, $quantidade, $validade, $perecivel) {
        try {
            $this->conn->beginTransaction();

            // 1️⃣ Inserir o Item
            $sqlItem = "INSERT INTO item (nome, codigo, fk_categoria_id, estoqueCritico, perecivel) 
                        VALUES (:nome, :codigo, :categoria, :estoqueCritico, :perecivel)";
            $stmtItem = $this->conn->prepare($sqlItem);
            $stmtItem->bindParam(":nome", $nome);
            $stmtItem->bindParam(":codigo", $codigo);
            $stmtItem->bindParam(":categoria", $categoria);
            $stmtItem->bindParam(":estoqueCritico", $estoqueCritico);
            $stmtItem->bindParam(":perecivel", $perecivel, PDO::PARAM_BOOL);
            $stmtItem->execute();

            // Pegar o ID do item recém-criado
            $itemId = $this->conn->lastInsertId();

            // 2️⃣ Registrar a Entrada Inicial na Movimentação
            $sqlMov = "INSERT INTO movimentacao (fk_item_id, tipo, quantidade, validade) 
                       VALUES (:itemId, 'entrada', :quantidade, :validade)";
            $stmtMov = $this->conn->prepare($sqlMov);
            $stmtMov->bindParam(":itemId", $itemId);
            $stmtMov->bindParam(":quantidade", $quantidade);
            $stmtMov->bindParam(":validade", $validade);

            if (empty($validade)) {
                $stmtMov->bindValue(":validade", null, PDO::PARAM_NULL);
            }

            $stmtMov->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function listarItens($termoBusca = "") {
        $sql = "SELECT i.*, c.nome AS categoria_nome 
                FROM item i
                INNER JOIN categoria c ON i.fk_categoria_id = c.id_categoria";

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

    public function listarEstoque() {
        $sql = "SELECT 
                    i.id_item, 
                    i.nome, 
                    i.estoqueCritico,
                    COALESCE(SUM(CASE WHEN m.tipo = 'entrada' THEN m.quantidade ELSE 0 END), 0) 
                  - COALESCE(SUM(CASE WHEN m.tipo = 'saida' THEN m.quantidade ELSE 0 END), 0) 
                    AS estoque_atual,
                    COALESCE((
                        SELECT m2.validade 
                        FROM movimentacao m2 
                        WHERE m2.fk_item_id = i.id_item 
                        AND m2.validade IS NOT NULL 
                        ORDER BY m2.validade DESC 
                        LIMIT 1
                    ), NULL) AS validade
                FROM item i
                LEFT JOIN movimentacao m ON i.id_item = m.fk_item_id
                GROUP BY i.id_item";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    

}
?>

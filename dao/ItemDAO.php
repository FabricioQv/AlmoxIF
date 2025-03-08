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
    public function cadastrarItem($nome, $codigo, $categoria, $estoqueCritico, $quantidade, $validade, $imagemNome) {
        try {
            $this->conn->beginTransaction();
    
            // Inserir o item no banco de dados
            $sqlItem = "INSERT INTO item (nome, codigo, fk_Categoria_id_categoria, estoqueCritico, imagem) 
                        VALUES (:nome, :codigo, :categoria, :estoqueCritico, :imagem)";
            $stmtItem = $this->conn->prepare($sqlItem);
            $stmtItem->bindValue(":nome", $nome, PDO::PARAM_STR);
            $stmtItem->bindValue(":codigo", $codigo, PDO::PARAM_STR);
            $stmtItem->bindValue(":categoria", $categoria, PDO::PARAM_INT);
            $stmtItem->bindValue(":estoqueCritico", $estoqueCritico ?? null, PDO::PARAM_INT);
            $stmtItem->bindValue(":imagem", $imagemNome ?? null, PDO::PARAM_STR);
    
            $stmtItem->execute();
            $itemId = $this->conn->lastInsertId();
    
            // Obtém o ID do usuário logado
            session_start();
            $usuarioId = $_SESSION["usuario"]["id_usuario"] ?? null;
            if (!$usuarioId) {
                throw new Exception("Usuário não autenticado. Impossível registrar movimentação.");
            }
    
            // Registrar movimentação com fk_usuario_id
            $sqlMov = "INSERT INTO movimentacao (fk_item_id, fk_usuario_id, tipo, quantidade, validade) 
                       VALUES (:itemId, :usuarioId, 'entrada', :quantidade, :validade)";
            $stmtMov = $this->conn->prepare($sqlMov);
            $stmtMov->bindValue(":itemId", $itemId, PDO::PARAM_INT);
            $stmtMov->bindValue(":usuarioId", $usuarioId, PDO::PARAM_INT);
            $stmtMov->bindValue(":quantidade", $quantidade, PDO::PARAM_INT);
            $stmtMov->bindValue(":validade", $validade ?? null, PDO::PARAM_STR);
    
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
                    i.imagem,
                    COALESCE(SUM(CASE WHEN m.tipo = 'entrada' THEN m.quantidade ELSE 0 END), 0) 
                  - COALESCE(SUM(CASE WHEN m.tipo = 'saida' THEN m.quantidade ELSE 0 END), 0) 
                    AS estoque_atual,
                    (SELECT MIN(m2.validade) 
                     FROM movimentacao m2 
                     WHERE m2.fk_item_id = i.id_item 
                     AND m2.validade IS NOT NULL 
                     AND m2.quantidade > 0
                     ORDER BY m2.validade ASC 
                     LIMIT 1) AS validade_mais_proxima,
                    CASE 
                        WHEN i.estoqueCritico IS NOT NULL AND 
                             (COALESCE(SUM(CASE WHEN m.tipo = 'entrada' THEN m.quantidade ELSE 0 END), 0) 
                              - COALESCE(SUM(CASE WHEN m.tipo = 'saida' THEN m.quantidade ELSE 0 END), 0)) < i.estoqueCritico
                        THEN 1 ELSE 0 
                    END AS estoque_baixo
                FROM item i
                LEFT JOIN movimentacao m ON i.id_item = m.fk_item_id
                GROUP BY i.id_item";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    
    
    

}
?>

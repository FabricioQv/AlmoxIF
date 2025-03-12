<?php
require_once "Database.php";

class ImportacaoDAO {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    // Verifica se um item já existe pelo nome
    private function existeItem($nome) {
        $sql = "SELECT id_item FROM item WHERE nome = :nome";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nome", $nome);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Verifica se uma categoria já existe pelo nome e retorna seu ID
    private function obterOuCriarCategoria($categoriaNome) {
        $sql = "SELECT id_categoria FROM categoria WHERE nome = :nome";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nome", $categoriaNome);
        $stmt->execute();
        $categoriaId = $stmt->fetchColumn();

        if (!$categoriaId) {
            // Criar a categoria se não existir
            $sqlInsert = "INSERT INTO categoria (nome) VALUES (:nome)";
            $stmtInsert = $this->conn->prepare($sqlInsert);
            $stmtInsert->bindParam(":nome", $categoriaNome);
            $stmtInsert->execute();
            return $this->conn->lastInsertId();
        }

        return $categoriaId;
    }

    public function importarDados($dados) {
        try {
            $this->conn->beginTransaction();
            
            // 🔹 Prepara as queries para execução
            $sqlItem = "INSERT INTO item (codigo, nome, unidade, fk_Categoria_id_categoria) 
                        VALUES (:codigo, :nome, :unidade, :categoria)";
            $stmtItem = $this->conn->prepare($sqlItem);
    
            $sqlMovimentacao = "INSERT INTO movimentacao (fk_item_id, fk_usuario_id, tipo, quantidade) 
                                VALUES (:item_id, :usuario_id, 'entrada', :quantidade)";
            $stmtMovimentacao = $this->conn->prepare($sqlMovimentacao);
    
            $usuarioId = $_SESSION["usuario"]["id_usuario"] ?? 1; // Se não houver sessão, usa um ID padrão
    
            foreach ($dados as $item) {
                // 🔹 Verifica ou cria a categoria
                $categoriaId = $this->obterOuCriarCategoria($item["categoria"]);
    
                // 🔹 Verifica se o item já existe pelo nome
                $itemId = $this->existeItem($item["nome"]);
    
                if (!$itemId) { // Se o item não existir, cria um novo
                    $stmtItem->bindParam(":codigo", $item["codigo"]);
                    $stmtItem->bindParam(":nome", $item["nome"]);
                    $stmtItem->bindParam(":unidade", $item["unidade"]);
                    $stmtItem->bindParam(":categoria", $categoriaId);
    
                    if ($stmtItem->execute()) {
                        $itemId = $this->conn->lastInsertId();
                    }
                } 
    
                // 🔹 Insere a movimentação do estoque
                if (!isset($item["estoque_atual"]) || empty($item["estoque_atual"])) {
                    echo "⚠️ Quantidade inválida para Item ID $itemId. Pulando movimentação. <br>";
                    continue;
                }

                $quantidade = intval($item["estoque_atual"]);

                $stmtMovimentacao->bindParam(":item_id", $itemId);
                $stmtMovimentacao->bindParam(":usuario_id", $usuarioId);
                $stmtMovimentacao->bindParam(":quantidade", $quantidade);
            }
    
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            die("Erro ao importar dados: " . $e->getMessage());
        }
    }
}
?>

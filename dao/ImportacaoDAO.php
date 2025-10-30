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

            $this->conn->exec("DELETE FROM log_movimentacao");
            $this->conn->exec("DELETE FROM movimentacao");
    
            // 🔹 Query para verificar se o código já existe
            $sqlVerificarItem = "SELECT id_item FROM item WHERE codigo = :codigo";
            $stmtVerificarItem = $this->conn->prepare($sqlVerificarItem);
    
            // 🔹 Query para criar um novo item
            $sqlInserirItem = "INSERT INTO item (codigo, nome, unidade, fk_Categoria_id_categoria) 
                               VALUES (:codigo, :nome, :unidade, :categoria)";
            $stmtInserirItem = $this->conn->prepare($sqlInserirItem);
    
            // 🔹 Query para atualizar a quantidade no estoque
            $sqlAtualizarMovimentacao = "UPDATE movimentacao 
                                         SET quantidade = quantidade + :quantidade 
                                         WHERE fk_item_id = :item_id AND tipo = 'entrada'";
            $stmtAtualizarMovimentacao = $this->conn->prepare($sqlAtualizarMovimentacao);
    
            // 🔹 Query para inserir uma movimentação se não houver entrada anterior
            $sqlInserirMovimentacao = "INSERT INTO movimentacao (fk_item_id, fk_usuario_id, tipo, quantidade) 
                                       VALUES (:item_id, :usuario_id, 'entrada', :quantidade)";
            $stmtInserirMovimentacao = $this->conn->prepare($sqlInserirMovimentacao);
    
            $usuarioId = $_SESSION["usuario"]["id_usuario"] ?? 1; // Se não houver sessão, usa um ID padrão
    
            foreach ($dados as $item) {
                // 🔹 Verifica ou cria a categoria
                $categoriaId = $this->obterOuCriarCategoria($item["categoria"]);
    
                // 🔹 Verifica se o item já existe pelo código
                $stmtVerificarItem->bindParam(":codigo", $item["codigo"]);
                $stmtVerificarItem->execute();
                $itemId = $stmtVerificarItem->fetchColumn(); // Obtém o ID do item se já existir
    
                if (!$itemId) { // Se o item não existir, cria um novo
                    $stmtInserirItem->bindParam(":codigo", $item["codigo"]);
                    $stmtInserirItem->bindParam(":nome", $item["nome"]);
                    $stmtInserirItem->bindParam(":unidade", $item["unidade"]);
                    $stmtInserirItem->bindParam(":categoria", $categoriaId);
    
                    if ($stmtInserirItem->execute()) {
                        $itemId = $this->conn->lastInsertId();
                    }
                }
    
                // 🔹 Atualiza o estoque se já existir o item
                $stmtAtualizarMovimentacao->bindParam(":item_id", $itemId);
                $stmtAtualizarMovimentacao->bindParam(":quantidade", $item["estoque_atual"]);
                $stmtAtualizarMovimentacao->execute();
    
                // 🔹 Se não há movimentação anterior para o item, insere uma nova entrada
                if ($stmtAtualizarMovimentacao->rowCount() == 0) {
                    $stmtInserirMovimentacao->bindParam(":item_id", $itemId);
                    $stmtInserirMovimentacao->bindParam(":usuario_id", $usuarioId);
                    $stmtInserirMovimentacao->bindParam(":quantidade", $item["estoque_atual"]);
                    $stmtInserirMovimentacao->execute();
                }
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

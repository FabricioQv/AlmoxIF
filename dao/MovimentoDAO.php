<?php
require_once "Database.php";
require_once "../classes/Movimento.php";

class MovimentoDAO {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function registrarMovimento($movimento) {
        try {
            $this->conn->beginTransaction();
    
            $sql = "INSERT INTO movimentacao (fk_item_id, fk_usuario_id, tipo, quantidade, validade, observacao) 
                    VALUES (:item, :usuario, :tipo, :quantidade, :validade, :observacao)";
    
            $stmt = $this->conn->prepare($sql);
    
            // ✅ Primeiro armazenamos em variáveis para evitar o notice
            $itemId = $movimento->getItemId();
            $usuarioId = $movimento->getUsuarioId();
            $tipo = $movimento->getTipo();
            $quantidade = $movimento->getQuantidade();
            $validade = $movimento->getValidade();
            $observacao = $movimento->getObservacao();
    
            // ✅ Agora usamos as variáveis no bindParam
            $stmt->bindParam(":item", $itemId);
            $stmt->bindParam(":usuario", $usuarioId);
            $stmt->bindParam(":tipo", $tipo);
            $stmt->bindParam(":quantidade", $quantidade);
            $stmt->bindParam(":validade", $validade);
            $stmt->bindParam(":observacao", $observacao);
    
            if (!$stmt->execute()) {
                $this->conn->rollBack();
                return false;
            }
    
            // Registrar log de movimentação com a observação
            $logSucesso = $this->registrarLog(
                $itemId,
                $usuarioId,
                $tipo,
                $quantidade,
                $validade,
                $observacao
            );
    
            if (!$logSucesso) {
                $this->conn->rollBack();
                return false;
            }
    
            $this->conn->commit(); // Confirma as alterações
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack(); // Desfaz qualquer alteração em caso de erro
            return false;
        }
    }
    

    public function listarMovimentacoes() {
        $sql = "SELECT 
                    l.id_log AS id,
                    i.nome AS item_nome,
                    u.nome AS usuario_nome,
                    l.tipo,
                    l.quantidade,
                    l.validade,
                    DATE_FORMAT(l.data_log, '%d/%m/%Y %H:%i') AS data_movimento,
                    l.descricao AS observacao
                FROM log_movimentacao l
                INNER JOIN item i ON l.fk_item_id = i.id_item
                INNER JOIN usuario u ON l.fk_usuario_id = u.id_usuario
                ORDER BY data_log DESC";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    public function obterMovimentacaoMensal($itemId = null) {
        $sql = "SELECT 
                    DATE_FORMAT(data_movimento, '%Y-%m') AS mes, 
                    SUM(CASE WHEN tipo = 'entrada' THEN quantidade ELSE 0 END) AS total_entrada,
                    SUM(CASE WHEN tipo = 'saida' THEN quantidade ELSE 0 END) AS total_saida
                FROM movimentacao";
        
        if ($itemId) {
            $sql .= " WHERE fk_item_id = :item_id";
        }
    
        $sql .= " GROUP BY mes ORDER BY mes ASC";
    
        $stmt = $this->conn->prepare($sql);
    
        if ($itemId) {
            $stmt->bindParam(":item_id", $itemId, PDO::PARAM_INT);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obterItensMaisMovimentados($itemId = null) {
        $sql = "SELECT 
                    i.nome AS item_nome, 
                    SUM(m.quantidade) AS total_movimentado
                FROM movimentacao m
                INNER JOIN item i ON m.fk_item_id = i.id_item";
    
        if ($itemId) {
            $sql .= " WHERE i.id_item = :item_id";
        }
    
        $sql .= " GROUP BY i.nome ORDER BY total_movimentado DESC LIMIT 5";
    
        $stmt = $this->conn->prepare($sql);
    
        if ($itemId) {
            $stmt->bindParam(":item_id", $itemId, PDO::PARAM_INT);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function gerarRelatorioMovimentacao($dataInicio, $dataFim) {
        $sql = "
          SELECT
    i.id_item,
    i.nome AS item_nome,

    -- Entradas do período (log)
    (
        SELECT COALESCE(SUM(lm.quantidade), 0)
        FROM log_movimentacao lm
        WHERE lm.fk_item_id = i.id_item
          AND lm.tipo = 'entrada'
          AND lm.data_log BETWEEN :dataInicio AND :dataFim
    ) AS total_entrada,

    -- Saídas do período (log)
    (
        SELECT COALESCE(SUM(lm.quantidade), 0)
        FROM log_movimentacao lm
        WHERE lm.fk_item_id = i.id_item
          AND lm.tipo = 'saida'
          AND lm.data_log BETWEEN :dataInicio AND :dataFim
    ) AS total_saida,

    -- Estoque final (somatório da tabela movimentacao)
    (
        SELECT COALESCE(SUM(m.quantidade), 0)
        FROM movimentacao m
        WHERE m.fk_item_id = i.id_item
    ) AS estoque_final,

    -- Estoque inicial calculado por fórmula:
    -- inicial = final - entradas + saídas
    (
        (
            SELECT COALESCE(SUM(m.quantidade), 0)
            FROM movimentacao m
            WHERE m.fk_item_id = i.id_item
        )
        -
        (
            SELECT COALESCE(SUM(lm.quantidade), 0)
            FROM log_movimentacao lm
            WHERE lm.fk_item_id = i.id_item
              AND lm.tipo = 'entrada'
              AND lm.data_log BETWEEN :dataInicio AND :dataFim
        )
        +
        (
            SELECT COALESCE(SUM(lm.quantidade), 0)
            FROM log_movimentacao lm
            WHERE lm.fk_item_id = i.id_item
              AND lm.tipo = 'saida'
              AND lm.data_log BETWEEN :dataInicio AND :dataFim
        )
    ) AS estoque_inicial

FROM item i

WHERE i.id_item IN (
    SELECT fk_item_id
    FROM log_movimentacao
    WHERE data_log BETWEEN :dataInicio AND :dataFim
)

ORDER BY i.nome;
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":dataInicio", $dataInicio);
        $stmt->bindParam(":dataFim", $dataFim);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarItemPorCodigo($codigo) {
        $sql = "SELECT * FROM item WHERE codigo = :codigo";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":codigo", $codigo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarLoteMaisAntigo($item_id) {
        $sql = "SELECT * FROM movimentacao 
                WHERE fk_item_id = :item_id AND tipo = 'entrada' 
                ORDER BY 
                    CASE 
                        WHEN validade IS NULL THEN 1 
                        ELSE 0 
                    END,
                    validade ASC, 
                    data_movimento ASC  
                LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":item_id", $item_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarEstoqueSimples() {
        $sql = "SELECT 
                    i.id_item, 
                    i.nome, 
                    i.codigo,
                    i.unidade,
                    i.imagem,
                    COALESCE(SUM(CASE WHEN m.tipo = 'entrada' THEN m.quantidade ELSE 0 END), 0) 
                  - COALESCE(SUM(CASE WHEN m.tipo = 'saida' THEN m.quantidade ELSE 0 END), 0) 
                    AS estoque_atual
                FROM item i
                LEFT JOIN movimentacao m ON i.id_item = m.fk_item_id
                GROUP BY i.id_item";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function listarEstoque() {
        $sql = "SELECT 
                    i.id_item, 
                    i.nome, 
                    i.codigo,
                    i.estoqueCritico,
                    i.imagem,
                    i.unidade,
                    c.nome AS categoria_nome,
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
                        WHEN i.estoqueCritico IS NOT NULL 
                             AND (COALESCE(SUM(CASE WHEN m.tipo = 'entrada' THEN m.quantidade ELSE 0 END), 0) 
                              - COALESCE(SUM(CASE WHEN m.tipo = 'saida' THEN m.quantidade ELSE 0 END), 0)) < i.estoqueCritico
                        THEN 1 ELSE 0 
                    END AS estoque_baixo
                FROM item i
                LEFT JOIN movimentacao m ON i.id_item = m.fk_item_id
                LEFT JOIN categoria c ON i.fk_Categoria_id_categoria = c.id_categoria 
                GROUP BY i.id_item, c.nome";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    public function removerItemFIFO($item_id, $quantidade, $observacao) {
        $usuario_id = $_SESSION["usuario"]["id_usuario"]; // Captura o usuário logado
    
        while ($quantidade > 0) {
            $lote = $this->buscarLoteMaisAntigo($item_id);
    
            if (!$lote) {
                return false; // Não há estoque suficiente
            }
    
            $quantidade_no_lote = $lote['quantidade'];
            $lote_id = $lote['id_movimentacao'];
            $validade = $lote['validade'];
    
            if ($quantidade_no_lote > $quantidade) {
                // Atualiza o lote reduzindo apenas a quantidade necessária
                $nova_quantidade = $quantidade_no_lote - $quantidade;
                $sql = "UPDATE movimentacao SET quantidade = :nova_quantidade 
                        WHERE id_movimentacao = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(":nova_quantidade", $nova_quantidade);
                $stmt->bindParam(":id", $lote_id);
                $stmt->execute();
    
                // Registrar log
                $this->registrarLog(
                    $item_id,
                    $usuario_id,
                    "saida",
                    $quantidade,
                    $validade,
                    $observacao
                );
    
                break;
            } else {
                // Remove o lote totalmente se a quantidade pedida for maior ou igual à disponível
                $sql = "DELETE FROM movimentacao WHERE id_movimentacao = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(":id", $lote_id);
                $stmt->execute();
                $quantidade -= $quantidade_no_lote;
    
                // Registrar log
                $this->registrarLog(
                    $item_id,
                    $usuario_id,
                    "saida",
                    $quantidade_no_lote,
                    $validade,
                    "$observacao"
                );
            }
        }
        return true;
    }
    
    
    public function registrarLog($item_id, $usuario_id, $tipo, $quantidade, $validade, $observacao) {
        $sql = "INSERT INTO log_movimentacao (fk_item_id, fk_usuario_id, tipo, quantidade, validade, descricao) 
                VALUES (:item_id, :usuario_id, :tipo, :quantidade, :validade, :observacao)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":item_id", $item_id);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->bindParam(":tipo", $tipo);
        $stmt->bindParam(":quantidade", $quantidade);
        $stmt->bindParam(":validade", $validade);
        $stmt->bindParam(":observacao", $observacao);
        
        return $stmt->execute();
    }    
}
?>

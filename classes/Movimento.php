<?php
class Movimento {
    private $itemId;
    private $usuarioId;
    private $tipo;
    private $quantidade;
    private $validade;
    private $observacao;

    public function __construct($itemId, $usuarioId, $tipo, $quantidade, $validade, $observacao) {
        $this->itemId = $itemId;
        $this->usuarioId = $usuarioId;
        $this->tipo = $tipo;
        $this->quantidade = $quantidade;
        $this->validade = $validade;
        $this->observacao = $observacao;
    }

    public function getItemId() {
        return $this->itemId;
    }

    public function getUsuarioId() {
        return $this->usuarioId;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }

    public function getValidade() {
        return $this->validade;
    }

    public function getObservacao() {
        return $this->observacao;
    }

    public function setItemId($itemId) {
        $this->itemId = $itemId;
    }
    
    public function setUsuarioId($usuarioId) {
        $this->usuarioId = $usuarioId;
    }
    
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }
    
    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }
    
    public function setValidade($validade) {
        $this->validade = $validade;
    }
    
    public function setObservacao($observacao) {
        $this->observacao = $observacao;
    }
    
}

?>

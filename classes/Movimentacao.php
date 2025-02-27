<?php
class Movimento {

    private $id;
    private $itemId;
    private $tipo;
    private $quantidade;
    private $validade;

    public function __construct($id, $itemId, $tipo, $quantidade, $validade) {
        $this->id = $id;
        $this->itemId = $itemId;
        $this->tipo = $tipo;
        $this->quantidade = $quantidade;
        $this->validade = $validade;
    }

    public function getValidade() {
        return $this->validade ? date("d/m/Y", strtotime($this->validade)) : "Não Perecível";
    }

    public function getItemId() {
        return $this->fk_Item_id_item;
    }

    public function getUsuarioId() {
        return $this->fk_Usuario_id_usuario;
    }

    public function getDataSaida() {
        return $this->dataSaida;
    }

    public function getDataEntrada() {
        return $this->dataEntrada;
    }

    public function getQuantidadeMovimentada() {
        return $this->quantidadeMovimentada;
    }

    public function setItemId($itemId) {
        $this->fk_Item_id_item = $itemId;
    }

    public function setUsuarioId($usuarioId) {
        $this->fk_Usuario_id_usuario = $usuarioId;
    }

    public function setDataSaida($dataSaida) {
        $this->dataSaida = $dataSaida;
    }

    public function setDataEntrada($dataEntrada) {
        $this->dataEntrada = $dataEntrada;
    }

    public function setQuantidadeMovimentada($quantidade) {
        $this->quantidadeMovimentada = $quantidade;
    }
}
?>

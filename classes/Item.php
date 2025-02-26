<?php
class Item {
    private $id_item;
    private $quantidadeEstoque;
    private $dataValidade;
    private $codigo;
    private $nome;
    private $fk_Categoria_id_categoria;

    public function getIdItem() {
        return $this->id_item;
    }

    public function getQuantidadeEstoque() {
        return $this->quantidadeEstoque;
    }

    public function getDataValidade() {
        return $this->dataValidade;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getCategoriaId() {
        return $this->fk_Categoria_id_categoria;
    }

    public function setQuantidadeEstoque($quantidade) {
        $this->quantidadeEstoque = $quantidade;
    }

    public function setDataValidade($data) {
        $this->dataValidade = $data;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setCategoriaId($categoriaId) {
        $this->fk_Categoria_id_categoria = $categoriaId;
    }
}
?>

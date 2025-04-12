<?php
class Item
{
    private $id_item;
    private $quantidadeEstoque;
    private $dataValidade;
    private $codigo;
    private $nome;
    private $fk_Categoria_id_categoria;


    public function __construct($id, $nome, $codigo, $categoria, $estoqueCritico, $dataValidade)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->codigo = $codigo;
        $this->categoria = $categoria;
        $this->estoqueCritico = $estoqueCritico;
        $this->dataValidade = $dataValidade;
    }

    public function getdataValidade()
    {
        return $this->dataValidade ? date("d/m/Y", strtotime($this->dataValidade)) : "Não Perecível";
    }

    public function getIdItem()
    {
        return $this->id_item;
    }

    public function getQuantidadeEstoque()
    {
        return $this->quantidadeEstoque;
    }

    public function getDatadataValidade()
    {
        return $this->dataValidade;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getCategoriaId()
    {
        return $this->fk_Categoria_id_categoria;
    }

    public function setQuantidadeEstoque($quantidade)
    {
        $this->quantidadeEstoque = $quantidade;
    }

    public function setDatadataValidade($data)
    {
        $this->datadataValidade = $data;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function setCategoriaId($categoriaId)
    {
        $this->fk_Categoria_id_categoria = $categoriaId;
    }
}
?>

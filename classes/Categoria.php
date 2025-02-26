<?php
class Categoria {
    private $id_categoria;
    private $nome;

    public function getIdCategoria() {
        return $this->id_categoria;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
}
?>

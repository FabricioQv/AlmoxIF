<?php
class Role {
    private $id_role;
    private $nome;

    public function getIdRole() {
        return $this->id_role;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
}
?>

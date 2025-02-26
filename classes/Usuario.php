<?php
class Usuario {
    private $id_usuario;
    private $nome;
    private $siape;
    private $login;
    private $senha;
    private $fk_Role_id_role;

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getSiape() {
        return $this->siape;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getRoleId() {
        return $this->fk_Role_id_role;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setSiape($siape) {
        $this->siape = $siape;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function setSenha($senha) {
        $this->senha = password_hash($senha, PASSWORD_BCRYPT);
    }

    public function setRoleId($roleId) {
        $this->fk_Role_id_role = $roleId;
    }
}
?>

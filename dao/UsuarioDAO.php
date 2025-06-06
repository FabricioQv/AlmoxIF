<?php
require_once "Database.php";
require_once "../classes/Usuario.php";

class UsuarioDAO {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function registrarUsuario($usuario) {
        if ($this->existeUsuario($usuario->getLogin())) {
            return false; // Retorna falso se o login já existir
        }

        $sql = "INSERT INTO usuario (nome, siape, login, senha, fk_Role_id_role) 
                VALUES (:nome, :siape, :login, :senha, :role)";
        $stmt = $this->conn->prepare($sql);
        $nome  = $usuario->getNome();
        $siape = $usuario->getSiape();
        $login = $usuario->getLogin();
        $senha = $usuario->getSenha();
        $role  = $usuario->getRoleId();

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":siape", $siape);
        $stmt->bindParam(":login", $login);
        $stmt->bindParam(":senha", $senha);
        $stmt->bindParam(":role", $role);

        return $stmt->execute();
    }

    public function existeUsuario($login) {
        $sql = "SELECT COUNT(*) FROM usuario WHERE login = :login";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":login", $login);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function login($login, $senha) {
        $sql = "SELECT * FROM usuario WHERE login = :login";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":login", $login);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario["senha"])) {
            return $usuario; // Retorna os dados do usuário se a senha estiver correta
        }
        return false; // Login inválido
    }

    public function verificarSenha($id_usuario, $senha) {
        $sql = "SELECT senha FROM usuario WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id_usuario);
        $stmt->execute();
        $hash = $stmt->fetchColumn();
        return password_verify($senha, $hash);
    }
    
    public function atualizarPerfil($id_usuario, $siape, $nova_senha, $confirmar_senha) {
        if (!empty($nova_senha) && $nova_senha !== $confirmar_senha) {
            return false;
        }
    
        $sql = "UPDATE usuario SET siape = :siape";
        if (!empty($nova_senha)) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql .= ", senha = :senha";
        }
        $sql .= " WHERE id_usuario = :id";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":siape", $siape);
        if (!empty($nova_senha)) {
            $stmt->bindParam(":senha", $nova_senha_hash);
        }
        $stmt->bindParam(":id", $id_usuario);
        return $stmt->execute();
    }
    
    public function listarTodos() {
        $sql = "SELECT * FROM usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function excluirUsuario($id_usuario) {
        $sql = "DELETE FROM usuario WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id_usuario);
        return $stmt->execute();
    }
    
}
?>

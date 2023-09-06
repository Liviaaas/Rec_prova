<?php
include_once('conexao/conexao.php');

$db = new Database();

class Crud{
    private $conn;
    private $table_name = "livros";

    public function __construct($db){
        $this->conn = $db;
    }

    //função para criar registros

    public function create($postValues){
        $nome = $postValues['nome'];
        $autor = $postValues['autor'];
        $genero = $postValues['genero'];
        $qtdpag = $postValues['qtdpag'];
        $preco = $postValues['preco'];

        $query = "INSERT INTO ". $this->table_name . " (nome, autor, genero, qtdpag, preco) VALUES (?,?,?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$nome);
        $stmt->bindParam(2,$autor);
        $stmt->bindParam(3,$genero);
        $stmt->bindParam(4,$qtdpag);
        $stmt->bindParam(5,$preco);

        $rows = $this->read();
        if($stmt->execute()){
            print "<script>alert ('Cadastro OK! ')</script>";
            print "<script> location.href='?action=read'; </script>";

            return true;

        }else{
            return false;
        }
    }

    //função para ler os registros

    public function read(){
        $query = "SELECT * FROM ". $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;

    }

    //função atualizar registros
    public function update($postValues){
        $id = $postValues['id'];
        $nome = $postValues['nome'];
        $autor = $postValues['autor'];
        $genero = $postValues['genero'];
        $qtdpag = $postValues['qtdpag'];
        $preco = $postValues['preco'];

        if (empty($id) || empty($nome) || empty($autor) || empty($genero) || empty($qtdpag) || empty($preco)){
            return false;
        }
        $query = "UPDATE ". $this->table_name . " SET nome = ?, autor = ?, genero = ?, qtdpag = ?, preco = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1,$nome);
        $stmt->bindParam(2,$autor);
        $stmt->bindParam(3,$genero);
        $stmt->bindParam(4,$qtdpag);
        $stmt->bindParam(5,$preco);
        $stmt->bindParam(6,$id);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }

     //função para pegar os registros do banco e inserir no formulário
     public function readOne($id){
        $query = "SELECT * FROM ". $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //  função function apagar os registros
    public function delete($id){
        $query = "DELETE FROM ". $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$id);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
}
?>
<?php
require_once('classes/Crud.php');
require_once('conexao/conexao.php'); //incluindo os arquivos

//criando instâncias e obtendo conexão com o banco de dados
$database = new Database();
$db = $database->getConnection();
$crud = new Crud($db);

if(isset($_GET['action'])){
    switch($_GET['action']){
        case 'create': //criar
            $crud->create($_POST);
            $rows = $crud->read();
            break;
        case 'read': //ler
            $rows = $crud->read();
            break;
        case 'update': //atualizar
            if(isset($_POST['id'])){
                $crud->update($_POST);
            }
            $rows = $crud->read();
            break;

            // deletar
            case 'delete':
                $crud->delete($_GET['id']);
                $rows = $crud->read();
                break;

                default:
                $rows = $crud->read();
                break;
    }
}else{
    $rows = $crud->read();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>

<style>

    body{
        background-color: rgb(169, 203, 209);
    }
    form {
        max-width: 500px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
       
    }

    label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        color: #333;
    }

    input[type="text"] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: rgb(97, 181, 196);
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        margin-top: 10px;
        
        
    }

    input[type="submit"]:hover {
        background-color: rgb(79, 172, 186);
    }

    table {
        border-collapse: collapse;
        width: 100%;
        font-family: Arial, sans-serif;
        font-size: 14px;
        color: #333;
        margin-top: 20px;
        background-color: #ffff;
    }

    h1 {
        font-size: 24px; /* Tamanho da fonte */
        color: #333; /* Cor do texto */
        text-align: center; /* Alinhamento do texto */
        margin-top: 20px;
    }

    th,
    td {
        text-align: left;
        padding: 8px;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    a {
        display: inline-block;
        padding: 4px 8px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
        margin-top: 10px;
    }

    a:hover {
        background-color: #0069d9;
    }

    a.delete {
        background-color: #dc3545;
    }

    a.delete:hover {
        background-color: #c82333;
    }
</style>


    <h1>Livros</h1>
</head>
<body>

<?php

//Este código verifica se o usuário está tentando atualizar um registro específico com base no parâmetro "id" 
//na URL. Se essa condição for verdadeira, ele lê os detalhes desse registro do banco de dados e armazena esses 
//detalhes na variável $result, que pode ser usada para edição ou exibição das informações.

    if(isset($_GET['action']) && $_GET['action']== 'update' && isset($_GET['id'])){
        $id = $_GET['id'];
        $result = $crud->readOne($id);

        if(!$result){
            echo "Registro não encontrado";
            exit();
        }
        $nome = $result['nome'];
        $autor = $result['autor'];
        $genero = $result['genero'];
        $qtdpag = $result['qtdpag'];
        $preco = $result['preco'];

?>

<form action= "?action=update" method="POST">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    
    <label for="nome">Nome do Livro</label>
    <input type="text" name="nome" value="<?php echo $nome ?>">
    
    <label for="autor">Autor</label>
    <input type="text" name="autor" value="<?php echo $autor ?>">

    <label for="genero">Gênero</label>
    <input type="text" name="genero" value="<?php echo $genero ?>">

    <label for="qtdpag">Quantidade de páginas</label>
    <input type="text" name="qtdpag" value="<?php echo $qtdpag ?>">

    <label for="preco">Preço</label>
    <input type="text" name="preco" value="<?php echo $preco ?>">

    <input type="submit" value="Atualizar" name="enviar" onclick="return confirm('Certeza que deseja atualizar?')">
</form>

<?php

}else{
    
?>

<form action="?action=create" method="POST">
    <label for="nome">Nome do Livro</label>
    <input type="text" name="nome">

    <label for="autor">Autor</label>
    <input type="text" name="autor">

    <label for="genero">Gênero</label>
    <input type="text" name="genero">

    <label for="qtdpag">Quantidade de páginas</label>
    <input type="text" name="qtdpag">

    <label for="preco">Preço</label>
    <input type="text" name="preco">

    <input type="submit" value="Cadastrar" name="enviar">

</form>
<?php
}
?>

<table>
    <try>
        <td>Id</td>
        <td>Nome do Livro</td>
        <td>Autor</td>
        <td>Gênero</td>
        <td>Quantidade de páginas</td>
        <td>Preço</td>
        <td>Ações</td> 
    </tr>
    <?php

if($rows->rowCount()==0){
    echo "<tr>";
    echo "<td calspan= '7'>Nenhum dado foi encontrado</td>";
    echo "</tr>";
} else {
    while($row = $rows->fetch(PDO::FETCH_ASSOC)){
        echo "<tr>";
      echo "<td>" . $row['id'] . "</td>";
      echo "<td>" . $row['nome'] . "</td>";
      echo "<td>" . $row['autor'] . "</td>";
      echo "<td>" . $row['genero'] . "</td>";       //atualizar ou excluir
      echo "<td>" . $row['qtdpag'] . "</td>";
      echo "<td>" . $row['preco'] . "</td>";
      echo "<td>";
      echo "<a href='?action=update&id=" . $row['id'] . "'>Atualizar</a>";
      echo "<a href='?action=delete&id=" . $row['id'] . "' onclick='return confirm(\"Tem certeza que quer apagar esse registro?\")' class='delete'>Deletar</a>";
      echo "</td>";
      echo "</tr>";
    }
}
?>
    </table>
</body>
</html>
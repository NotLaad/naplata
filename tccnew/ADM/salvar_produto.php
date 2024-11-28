<?php
session_start();
include '../DADOS/conexao.php';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_produto = $_POST['nome_produto'];
    $unidade = $_POST['unidade'];
    $valor = $_POST['valor'];

    $sql = "INSERT INTO Precos (Grp_desc, Grp_unidade_de_medida, Grp_Valor, Grp_Ativo_SN) 
            VALUES ('$nome_produto', '$unidade', '$valor', 'S')";

    if ($conn->query($sql) === TRUE) {
        echo "Produto cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar o produto: " . $conn->error;
    }

    $conn->close();
}
header('Location: criaproduto.php');
exit();
?>

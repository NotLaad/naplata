<?php
session_start();
include '../DADOS/conexao.php';  

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM Precos WHERE Grp_precos = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Produto deletado com sucesso!";
    } else {
        echo "Erro ao deletar produto: " . $conn->error;
    }

    $conn->close();
}

// Redireciona de volta para a página de criação de produtos
header('Location: criaproduto.php');
exit();
?>

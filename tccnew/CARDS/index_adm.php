<?php
// Corrigido para o caminho correto
include('../DADOS/conexao.php'); 
// Caminho correto para o arquivo 'conexao.php'
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="../styleadm.css">
    
    </head>
<body>
<header class="bordered">
    <div class="inicio-container">
        <img src="../UPLOAD/menu.png" alt="Ícone Início" class="inicio-icon">
        <span class="inicio-text">INÍCIO</span>
    </div>
    <img src="../UPLOAD/naplata_preto.png" alt="Logo" class="logo">
</header>
<div class="container">
    <h1>Painel do Administrador</h1>

    <div class="card-container">
      
        <div class="card">
            <a href="../ADM/criaproduto.php">
                <img src="../UPLOAD/preco (2).png" alt="Criar Produto" class="card-image">
                <h3>Criar Produto</h3>
            </a>
        </div>
        <div class="card">
            <a href="../ADM/cria_fichas.php">
                <img src="../UPLOAD/comando.png" alt="Criar Fichas" class="card-image">
                <h3>Criar Fichas</h3>
            </a>
        </div>
        <div class="card">
            <a href="balanca.php">
                <img src="../UPLOAD/bl.png" alt="Sistema Balança" class="card-image">
                <h3>Sistema Balança</h3>
            </a>
        </div>
        <div class="card">
            <a href="caixa.php">
                <img src="../UPLOAD/cx1.png" alt="Sistema Caixa" class="card-image">
                <h3>Sistema Caixa</h3>
            </a>
        </div>
        <div class="card">
            <a href="../ADM/relatorio.php">
                <img src="../UPLOAD/relatorio (2).png" alt="Relatório" class="card-image">
                <h3>Relatório</h3>
            </a>
        </div>
    </div>
</div>
</body>
</html>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Produto</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Referência ao arquivo CSS externo -->
    

<!-- Conteúdo da página criaproduto.php -->

</head>
<header class="bordered">
    <div class="inicio-container">
        <img src="../UPLOAD/menu.png" alt="Ícone Início" class="inicio-icon">
        <span class="inicio-text">INÍCIO</span>
    </div>
    <img src="../UPLOAD/naplata_preto.png" alt="Logo" class="logo">
</header>
<body>
    <div class="container">
        <h1>Criar Novo Produto</h1>
        <form action="salvar_produto.php" method="post">
            <label for="nome_produto">Nome do Produto:</label>
            <input type="text" id="nome_produto" name="nome_produto" required>

            <label for="unidade">Unidade de Medida:</label>
            <select id="unidade" name="unidade" required>
                <option value="un">Unidade</option>
                <option value="kg">Kg</option>
            </select>

            <label for="valor">Valor:</label>
            <input type="number" id="valor" name="valor" step="0.01" required>

            <button type="submit">Salvar Produto</button>
        </form>

        <!-- Exibição de Produtos Cadastrados -->
        <div class="product-list">
            <h2>Produtos Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Unidade</th>
                        <th>Valor</th>
                        <th>Ações</th> <!-- Nova coluna para ações -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Conexão com o banco de dados
                    session_start();
                    include '../DADOS/conexao.php';  


                    $sql = "SELECT Grp_precos AS id, Grp_desc AS nome_produto, Grp_unidade_de_medida AS unidade, Grp_Valor AS valor FROM Precos";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Exibir cada produto
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['nome_produto']}</td>
                                <td>{$row['unidade']}</td>
                                <td>R$ {$row['valor']}</td>
                                <td>
                                    <a href='editar_produto.php?id={$row['id']}'>Editar</a> | 
                                    <a href='deletar_produto.php?id={$row['id']}'>Deletar</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Nenhum produto cadastrado</td></tr>";
                    }

                    $conn->close();
                    ?>
                
                </tbody>
            </table>
        </div>
    </div>
    <a href="../CARDS/index_adm.php" class="botao-voltar" style="color: white;">Voltar</a>
</body>
</html>

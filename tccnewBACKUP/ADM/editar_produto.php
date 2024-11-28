<?php
session_start();
include '../DADOS/conexao.php';  

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Buscar os dados atuais do produto
    $sql = "SELECT Grp_desc, Grp_unidade_de_medida, Grp_Valor FROM Precos WHERE Grp_precos = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nome_produto = $row['Grp_desc'];
        $unidade = $row['Grp_unidade_de_medida'];
        $valor = $row['Grp_Valor'];
    }
}

// Atualizar o produto no banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_produto = $_POST['nome_produto'];
    $unidade = $_POST['unidade'];
    $valor = $_POST['valor'];

    $sql = "UPDATE Precos SET Grp_desc = '$nome_produto', Grp_unidade_de_medida = '$unidade', Grp_Valor = '$valor' WHERE Grp_precos = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Produto atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o produto: " . $conn->error;
    }

    $conn->close();
    header('Location: criaproduto.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="styles.css"> <!-- Referência ao arquivo CSS -->
</head>
<body>
    <div class="container">
        <h1>Editar Produto</h1>
        <form action="editar_produto.php?id=<?php echo $id; ?>" method="post">
            <label for="nome_produto">Nome do Produto:</label>
            <input type="text" id="nome_produto" name="nome_produto" value="<?php echo $nome_produto; ?>" required>

            <label for="unidade">Unidade de Medida:</label>
            <select id="unidade" name="unidade" required>
                <option value="un" <?php echo ($unidade == 'un') ? 'selected' : ''; ?>>Unidade</option>
                <option value="kg" <?php echo ($unidade == 'kg') ? 'selected' : ''; ?>>Kg</option>
            </select>

            <label for="valor">Valor:</label>
            <input type="number" id="valor" name="valor" step="0.01" value="<?php echo $valor; ?>" required>

            <button type="submit">Atualizar Produto</button>
        </form>
    </div>
</body>
</html>

<?php
// Conexão com o banco de dados
include('../DADOS/conexao.php');

// Inicializando variáveis de filtro
$ficha_cod = isset($_POST['ficha_cod']) ? $_POST['ficha_cod'] : '';
$data_compra = isset($_POST['data_compra']) ? $_POST['data_compra'] : '';

// Montando a consulta SQL com base nos filtros
$query = "SELECT * FROM Relatorio_Vendas WHERE 1=1";

// Adicionando filtro para o número da ficha, se fornecido
if (!empty($ficha_cod)) {
    $query .= " AND ficha_cod LIKE '%" . $conn->real_escape_string($ficha_cod) . "%'";
}

// Adicionando filtro para a data de compra, se fornecida
if (!empty($data_compra)) {
    $query .= " AND DATE(data_compra) = '" . $conn->real_escape_string($data_compra) . "'";
}

// Ordenar os resultados pela data mais recente
$query .= " ORDER BY data_compra DESC";

// Executando a consulta
$result = $conn->query($query);

// Fechando a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas</title>
    <link rel="stylesheet" href="../styles.css">
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
    <h1>Relatório de Vendas</h1>

    <!-- Formulário de filtro -->
    <form method="POST">
        <div>
            <label for="ficha_cod">Número da Ficha:</label>
            <input type="text" name="ficha_cod" id="ficha_cod" value="<?php echo htmlspecialchars($ficha_cod); ?>">
        </div>
        <div>
            <label for="data_compra">Data da Compra:</label>
            <input type="date" name="data_compra" id="data_compra" value="<?php echo htmlspecialchars($data_compra); ?>">
        </div>
        <button type="submit">Filtrar</button>
    </form>

    <!-- Tabela de resultados -->
    <?php if ($result->num_rows > 0): ?>
        <table class="tabela">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ficha</th>
                    <th>Valor Ficha</th>
                    <th>Valor Pago</th>
                    <th>Troco</th>
                    <th>Forma de Pagamento</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['ficha_cod']; ?></td>
                        <td>R$ <?php echo number_format($row['valor_ficha'], 2, ',', '.'); ?></td>
                        <td>R$ <?php echo number_format($row['valor_pago'], 2, ',', '.'); ?></td>
                        <td>R$ <?php echo number_format($row['troco'], 2, ',', '.'); ?></td>
                        <td><?php echo $row['forma_pagamento']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['data_compra'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="mensagem-notificacao">Nenhuma venda registrada ainda.</p>
    <?php endif; ?>

    <a href="../CARDS/index_adm.php" class="botao-voltar">Voltar</a>
</div>

</body>
</html>

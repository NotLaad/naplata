<?php
include '../DADOS/conexao.php';  // Conectar com o banco de dados

// Variáveis iniciais
$ficha_cod = 0;
$valor_ficha = 0;
$valor_pago = 0;
$troco = 0;
$formapagamento = "";
$valor_adicional = 0;
$produtos = [];  // Array para armazenar os produtos
$botao_nome = "Buscar Ficha";  // Nome do botão inicial
$botao_finalizar_disabled = "disabled"; // Botão finalização desabilitado

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados enviados pelo formulário
    $ficha_cod = isset($_POST['ficha_cod']) ? $_POST['ficha_cod'] : 0;
    $valor_pago = isset($_POST['valor_pago']) ? $_POST['valor_pago'] : 0;
    $formapagamento = isset($_POST['formapagamento']) ? $_POST['formapagamento'] : "";
    $valor_adicional = isset($_POST['valor_adicional']) ? $_POST['valor_adicional'] : 0;

    // Recupera o valor da ficha selecionada
    if ($ficha_cod > 0) {
        $stmt = $conn->prepare("SELECT Fic_valor_calculado, Fic_valor_inicial FROM Fichas WHERE Fic_cod = ?");
        $stmt->bind_param("i", $ficha_cod);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $valor_ficha = $row['Fic_valor_calculado']; // Obtém o valor calculado da ficha
            $valor_inicial = $row['Fic_valor_inicial']; // Obtém o valor inicial da ficha
            $botao_nome = "Calcular Troco";  // Mudando o nome do botão para "Calcular Troco"
        }
        $stmt->close();
    }

    // Se a forma de pagamento for dinheiro, calcula o troco
    if ($formapagamento == "dinheiro") {
        $valor_ficha = (float)$valor_ficha;  
        $valor_adicional = (float)$valor_adicional;  

        // Adiciona o valor adicional ao valor da ficha
        $valor_ficha += $valor_adicional;
        
        if ($valor_pago >= $valor_ficha) {
            $troco = $valor_pago - $valor_ficha;
        }
    }

    // Verifica se o valor pago foi inserido para habilitar o botão "Finalizar Compra"
    if ($valor_pago > 0) {
        $botao_finalizar_disabled = ""; // Habilita o botão finalizador
    }

    // Finaliza a compra, armazena os dados no banco
    if (isset($_POST['finalizar_compra']) && $valor_ficha > 0) {
        $data_compra = date('Y-m-d H:i:s'); 
        
        // Insere os dados da compra no relatório
        $insert_stmt = $conn->prepare("INSERT INTO Relatorio_Vendas (ficha_cod, valor_ficha, valor_pago, troco, forma_pagamento, data_compra) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("iddsss", $ficha_cod, $valor_ficha, $valor_pago, $troco, $formapagamento, $data_compra);
        $insert_stmt->execute();
        $insert_stmt->close();

        // Restaura o valor calculado da ficha ao valor inicial
        $update_stmt = $conn->prepare("UPDATE Fichas SET Fic_valor_calculado = Fic_valor_inicial WHERE Fic_cod = ?");
        $update_stmt->bind_param("i", $ficha_cod);
        $update_stmt->execute();
        $update_stmt->close();

        echo "<p class='mensagem-notificacao'>Compra finalizada. O valor da ficha foi restaurado ao original.</p>";
    }
}

// Recupera as fichas ativas para a seleção no formulário
$fichas_query = "SELECT Fic_cod FROM Fichas WHERE Fic_ativo_sn = 'S'";
$fichas_result = $conn->query($fichas_query);

$conn->close();
?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Caixa</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="script.js" defer></script>
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
    <h1>Sistema de Caixa</h1>

    <!-- Formulário de caixa -->
    <form method="post" action="caixa.php">
        <!-- Seleção da Ficha -->
        <div class="form-group">
            <label for="ficha_cod">Selecione uma Ficha:</label>
            <select name="ficha_cod" id="ficha_cod" required>
                <option value="">-- Selecione uma Ficha --</option>
                <?php
                if ($fichas_result->num_rows > 0) {
                    while ($ficha = $fichas_result->fetch_assoc()) {
                        $selected = ($ficha['Fic_cod'] == $ficha_cod) ? 'selected' : ''; 
                        echo "<option value='" . $ficha['Fic_cod'] . "' $selected>Ficha " . $ficha['Fic_cod'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhuma ficha ativa encontrada</option>";
                }
                ?>
            </select>
        </div>

        <!-- Exibe o valor da ficha, se disponível -->
        <?php if ($valor_ficha > 0): ?>
            <h2>Valor da Ficha: R$ <?php echo number_format($valor_ficha, 2, ',', '.'); ?></h2>
        <?php endif; ?>

        <!-- Adicionar valor adicional -->
        <div class="form-group">
            <label for="valor_adicional">Valor Adicional (R$):</label>
            <input type="number" id="valor_adicional" name="valor_adicional" value="<?php echo htmlspecialchars($valor_adicional); ?>" step="0.01">
        </div>

        <!-- Forma de pagamento -->
        <div class="form-group">
            <label for="formapagamento">Forma de Pagamento:</label>
            <select name="formapagamento" id="formapagamento" required>
                <option value="">-- Selecione a Forma de Pagamento --</option>
                <option value="dinheiro" <?php echo ($formapagamento == 'dinheiro') ? 'selected' : ''; ?>>Dinheiro</option>
                <option value="cartao" <?php echo ($formapagamento == 'cartao') ? 'selected' : ''; ?>>Cartão</option>
            </select>
        </div>

        <!-- Campo de valor pago, se o pagamento for em dinheiro -->
        <?php if ($formapagamento == "dinheiro"): ?>
            <div class="form-group">
                <label for="valor_pago">Valor Pago (R$):</label>
                <input type="number" id="valor_pago" name="valor_pago" value="<?php echo htmlspecialchars($valor_pago); ?>" step="0.01" required>
            </div>
        <?php endif; ?>

        <!-- Botões -->
        <div class="actions">
            <button type="submit" name="calcular" class="btn-calcular"><?php echo $botao_nome; ?></button>

            <?php if ($valor_ficha > 0 && ($formapagamento == 'dinheiro' || $formapagamento == 'cartao')): ?>
                <button type="submit" name="finalizar_compra" class="btn-finalizar" <?php echo $botao_finalizar_disabled; ?> title="Insira o valor pago para finalizar">Finalizar Compra</button>
            <?php endif; ?>
        </div>
    </form>

    <!-- Exibe o troco, se necessário -->
    <?php if ($formapagamento == "dinheiro" && $troco > 0): ?>
        <h3>Troco: R$ <?php echo number_format($troco, 2, ',', '.'); ?></h3>
    <?php endif; ?>

    <a href="index_adm.php" class="botao-voltar">Voltar</a>
</div>
</body>
</html>

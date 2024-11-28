<?php
include('../DADOS/conexao.php'); // Caminho correto para o arquivo 'conexao.php'

// Inicializando as variáveis
$valor_total = 0; // Valor do último produto adicionado
$peso = 0;
$ficha_cod = 0;
$produto_desc = '';
$valor_acumulado = 0; // Valor acumulado da ficha no banco

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    if (isset($_POST['ficha_cod'])) {
        $ficha_cod = $_POST['ficha_cod'];
    }

    if (isset($_POST['produto_desc']) && isset($_POST['peso'])) {
        $produto_desc = $_POST['produto_desc'];
        $peso = $_POST['peso'];
    }

    // Recupera o valor acumulado atual da ficha
    if ($ficha_cod > 0) {
        $query_atual = $conn->prepare("SELECT Fic_valor_calculado FROM Fichas WHERE Fic_cod = ?");
        $query_atual->bind_param("i", $ficha_cod);
        $query_atual->execute();
        $resultado_atual = $query_atual->get_result();

        if ($resultado_atual->num_rows > 0) {
            $linha_atual = $resultado_atual->fetch_assoc();
            $valor_acumulado = $linha_atual['Fic_valor_calculado']; // Valor total acumulado no banco
        }
        $query_atual->close();
    }

    // Verifica se o peso/quantidade é maior que 0 e a ficha foi selecionada
    if ($peso > 0 && $ficha_cod > 0 && !empty($produto_desc)) {
        // Consulta o preço do produto baseado na descrição
        $stmt = $conn->prepare("SELECT * FROM Precos WHERE Grp_desc = ? AND Grp_Ativo_SN = 'S'");
        $stmt->bind_param("s", $produto_desc);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se o produto foi encontrado
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Calcula o valor total do produto (considerando o preço e peso)
            $valor_unitario = $row['Grp_Valor'];
            $valor_total = $valor_unitario * $peso;

            // Soma o valor total do novo produto ao valor acumulado
            $valor_final = $valor_acumulado + $valor_total;

            // Atualiza o banco de dados apenas quando o botão "Registrar Valor na Ficha" for clicado
            if (isset($_POST['registrar_valor'])) {
                $update_stmt = $conn->prepare("UPDATE Fichas SET Fic_valor_calculado = ? WHERE Fic_cod = ?");
                $update_stmt->bind_param("di", $valor_final, $ficha_cod);
                $update_stmt->execute();
                $update_stmt->close();

                echo "<br>Valor atualizado com sucesso na ficha!";
            }

            // Exibe o valor acumulado atualizado
            $valor_acumulado = $valor_final;
        } else {
            echo "Erro: Produto não encontrado ou inativo.";
        }

        // Fecha a declaração
        $stmt->close();
    } else {
        echo "Erro: Preencha todos os campos corretamente.";
    }
}

// Recupera as fichas ativas para a seleção no formulário
$fichas_query = "SELECT Fic_cod FROM Fichas WHERE Fic_ativo_sn = 'S'";
$fichas_result = $conn->query($fichas_query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Caixa - Balança</title>
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
    <h1>Balança</h1>

    <form method="post" action="balanca.php">
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

        <label for="produto_desc">Nome do Produto:</label>
        <input type="text" id="produto_desc" name="produto_desc" value="<?php echo htmlspecialchars($produto_desc); ?>" required>

        <label for="peso">Peso/Quantidade:</label>
        <input type="number" id="peso" name="peso" value="<?php echo htmlspecialchars($peso); ?>" required step="0.01">

        <button type="submit">Adicionar Produto</button>

        <?php if ($valor_total > 0 && $ficha_cod > 0): ?>
            <button type="submit" name="registrar_valor">Registrar Valor na Ficha</button>
        <?php endif; ?>

    </form>

    <h2>Valor Total da Ficha: R$ <?php echo number_format($valor_acumulado, 2, ',', '.'); ?></h2>

    <a href="index_adm.php" class="botao-voltar" style="color: white;">Voltar</a>
</div>
</body>
</html>

<?php
// Fechando a conexão com o banco de dados no final do script
$conn->close();
?>

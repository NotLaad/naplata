<?php
// Inicia a sessão e inclui a conexão com o banco
session_start();
include '../DADOS/conexao.php';

// Verifica se o formulário foi enviado para atualizar o valor
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novo_valor_ficha = $_POST['valor_ficha'];

    // Atualizar o valor da ficha no banco de dados
    $stmt_update = $conn->prepare("UPDATE Configuracao SET valor_ficha = ? WHERE id = 1");
    $stmt_update->bind_param("d", $novo_valor_ficha);
    if ($stmt_update->execute()) {
        echo "<script>alert('Valor atualizado com sucesso!');</script>";

        // Agora atualiza as fichas existentes com o novo valor
        $stmt_fichas = $conn->prepare("UPDATE Fichas SET Fic_valor_calculado = ? WHERE Fic_ativo_sn = 'S'");
        $stmt_fichas->bind_param("d", $novo_valor_ficha);
        $stmt_fichas->execute();
    } else {
        echo "<script>alert('Erro ao atualizar o valor: " . $stmt_update->error . "');</script>";
    }
    $stmt_update->close();
    $stmt_fichas->close();
}

// Obtém o valor atual para exibição no formulário
$stmt_valor = $conn->prepare("SELECT valor_ficha FROM Configuracao LIMIT 1");
$stmt_valor->execute();
$stmt_valor->bind_result($valor_ficha_atual);
$stmt_valor->fetch();
$stmt_valor->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Valor da Ficha</title>
</head>
<body>
    <h1>Alterar Valor da Ficha</h1>
    <form method="post" action="alterar_valor.php">
        <label for="valor_ficha">Novo valor da ficha:</label>
        <input type="number" step="0.01" id="valor_ficha" name="valor_ficha" value="<?= number_format($valor_ficha_atual, 2, ',', '.') ?>" required>
        <button type="submit">Salvar Alteração</button>
    </form>
</body>
</html>

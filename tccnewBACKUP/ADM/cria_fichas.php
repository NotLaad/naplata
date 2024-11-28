<?php
// Inicia a sessão e inclui a conexão com o banco
session_start();
include '../DADOS/conexao.php';

// Verifica se o formulário foi enviado para cadastrar uma nova ficha
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fic_cod'])) {
    $fic_cod = filter_var($_POST['fic_cod'], FILTER_VALIDATE_INT);

    // Verifica se o código da ficha não está vazio e é válido
    if ($fic_cod !== false && !empty($fic_cod)) {
        // Verifica se o código já existe
        $stmt = $conn->prepare("SELECT Fic_cod FROM Fichas WHERE Fic_cod = ?");
        $stmt->bind_param("i", $fic_cod);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Código de ficha já existe! Escolha um código diferente.');</script>";
        } else {
            // Obtém o valor padrão das fichas
            $stmt_valor = $conn->prepare("SELECT valor_ficha FROM Configuracao LIMIT 1");
            $stmt_valor->execute();
            $stmt_valor->bind_result($valor_ficha);
            $stmt_valor->fetch();
            $stmt_valor->close();

            // Prepara a query para inserir a ficha no banco
            $stmt = $conn->prepare("INSERT INTO Fichas (Fic_cod, Fic_ativo_sn, Fic_dt_inclusao, Fic_valor_calculado) VALUES (?, 'S', NOW(), ?)");
            $stmt->bind_param("id", $fic_cod, $valor_ficha);

            // Executa a query e trata erros
            if ($stmt->execute()) {
                echo "<script>alert('Ficha criada com sucesso!');</script>";
            } else {
                echo "<script>alert('Erro ao criar ficha: " . htmlspecialchars($stmt->error) . "');</script>";
            }
        }

        // Fecha o statement
        $stmt->close();
    } else {
        echo "<script>alert('O código da ficha é obrigatório e deve ser um número válido!');</script>";
    }
}

// Verifica se o formulário foi enviado para alterar o valor da ficha
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['valor_ficha'])) {
    $novo_valor_ficha = filter_var($_POST['valor_ficha'], FILTER_VALIDATE_FLOAT);

    // Verifica se o valor é válido
    if ($novo_valor_ficha !== false) {
        // Atualiza o valor da ficha no banco de dados
        $stmt_update = $conn->prepare("UPDATE Configuracao SET valor_ficha = ? WHERE id = 1");
        $stmt_update->bind_param("d", $novo_valor_ficha);
        if ($stmt_update->execute()) {
            echo "<script>alert('Valor atualizado com sucesso!');</script>";

            // Atualiza as fichas existentes com o novo valor
            $stmt_fichas = $conn->prepare("UPDATE Fichas SET Fic_valor_calculado = ? WHERE Fic_ativo_sn = 'S'");
            $stmt_fichas->bind_param("d", $novo_valor_ficha);
            $stmt_fichas->execute();
        } else {
            echo "<script>alert('Erro ao atualizar o valor: " . htmlspecialchars($stmt_update->error) . "');</script>";
        }
        $stmt_update->close();
        $stmt_fichas->close();
    } else {
        echo "<script>alert('O valor da ficha deve ser um número válido!');</script>";
    }
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
    <title>Criar Ficha</title>
    <link rel="stylesheet" href="../styles.css">
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
        <h1>Criar Nova Ficha</h1>
        
        <!-- Formulário para Alterar Valor da Ficha -->
        <form method="post" action="cria_fichas.php">
            <label for="valor_ficha">Novo valor da ficha:</label>
            <input type="number" step="0.01" id="valor_ficha" name="valor_ficha" value="<?= number_format($valor_ficha_atual, 2, ',', '.') ?>" required>
            <button type="submit">Salvar Alteração</button>
        </form>

        <h2>Cadastrar Nova Ficha</h2>
        <!-- Formulário para cadastrar a ficha -->
        <form method="post" action="cria_fichas.php">
            <label for="fic_cod">Código da Ficha:</label>
            <input type="number" id="fic_cod" name="fic_cod" required>
            <button type="submit">Cadastrar Ficha</button>
        </form>

        <h2>Fichas Cadastradas</h2>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Valor</th>
                    <th>Data de Inclusão</th>
                    <th>Data de Desativação</th>
                    <th>Ativo</th>
                    <th>Descrição da Desativação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para buscar as fichas cadastradas
                $sql = "SELECT Fic_cod, Fic_valor_calculado, Fic_ativo_sn, Fic_dt_inclusao, Fic_dt_desativacao, Fic_desc_desativacao FROM Fichas";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Exibir cada ficha
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['Fic_cod']}</td>
                            <td>R$ " . number_format($row['Fic_valor_calculado'], 2, ',', '.') . "</td>
                            <td>{$row['Fic_dt_inclusao']}</td>
                            <td>{$row['Fic_dt_desativacao']}</td>
                            <td>{$row['Fic_ativo_sn']}</td>
                            <td>{$row['Fic_desc_desativacao']}</td>
                            <td>
                                <a href='editar_ficha.php?fic_cod={$row['Fic_cod']}'>Editar</a> | 
                                <a href='deletar_ficha.php?fic_cod={$row['Fic_cod']}'>Deletar</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Nenhuma ficha cadastrada</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <a href="../CARDS/index_adm.php" class="botao-voltar" style="color: white;">Voltar</a>
</body>
</html>

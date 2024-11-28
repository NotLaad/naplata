<?php

session_start();
include '../DADOS/conexao.php';    // Caminho correto para o arquivo 'conexao.php'

// O restante do seu código aqui...


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifique se os índices existem antes de acessá-los
    if (isset($_POST['usu_id']) && isset($_POST['usu_nome']) && isset($_POST['usu_email']) && isset($_POST['usu_senha']) && isset($_POST['usu_tipo']) && isset($_POST['usu_status'])) {
        
        $cod = $_POST['usu_id'];
        $nome = $_POST['usu_nome'];
        $email = $_POST['usu_email'];
        $senha = password_hash($_POST['usu_senha'], PASSWORD_DEFAULT); // Armazenando a senha de forma segura
        $tipo = $_POST['usu_tipo'];
        $status = $_POST['usu_status'];

        // Prepare a declaração SQL
        $sql = "INSERT INTO usuarios (usu_id, usu_email, usu_nome, usu_senha, usu_tipo, usu_status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $cod, $email, $nome, $senha, $tipo, $status);

        if ($stmt->execute()) {
            echo "Novo usuário criado com sucesso!";
        } else {
            echo "Erro ao criar usuário: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Erro: Dados do formulário não foram enviados corretamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Novo Usuário</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Criar Novo Usuário</h1>
        <form method="post" action="adm.php">
            <label for="usu_id">ID:</label>
            <input type="number" id="usu_id" name="usu_id" required>

            <label for="usu_nome">Nome:</label>
            <input type="text" id="usu_nome" name="usu_nome" required>

            <label for="usu_email">Email:</label>
            <input type="email" id="usu_email" name="usu_email" required>

            <label for="usu_senha">Senha:</label>
            <input type="password" id="usu_senha" name="usu_senha" value="1234" required>

            <label for="usu_tipo">Tipo:</label>
            <select id="usu_tipo" name="usu_tipo" required>
                <option value="adm">ADM</option>
                <option value="caixa">Funcionário</option>
                <option value="balanca">Balança</option>
            </select>

            <label for="usu_status">Status:</label>
            <select id="usu_status" name="usu_status" required>
                <option value="S">Ativo</option>
                <option value="N">Inativo</option>
            </select>

            <button type="submit">Criar Usuário</button>
        </form>
    </div>
</body>
</html>

<?php
include 'conexao.php';  // Conectar com o banco de dados

// Inserir usuários padrão com senha hash
$senha_adm = password_hash('1234', PASSWORD_DEFAULT);
$senha_funcionario = password_hash('1234', PASSWORD_DEFAULT);
$senha_balanca = password_hash('1234', PASSWORD_DEFAULT);

// Inserir usuário ADM
$sql_adm = "INSERT INTO usuarios (usu_email, usu_nome, usu_senha, usu_tipo, usu_status) 
            VALUES ('adm@gmail.com', 'Admin', ?, 'adm', 'S')";
// Preparar e executar a consulta
$stmt = $conn->prepare($sql_adm);
$stmt->bind_param("s", $senha_adm);  // Bind da senha hash
$stmt->execute();

// Inserir usuário Funcionário
$sql_funcionario = "INSERT INTO usuarios (usu_email, usu_nome, usu_senha, usu_tipo, usu_status) 
                    VALUES ('funcionario@gmail.com', 'Funcionário', ?, 'funcionario', 'S')";
$stmt = $conn->prepare($sql_funcionario);
$stmt->bind_param("s", $senha_funcionario);
$stmt->execute();

// Inserir usuário Balança
$sql_balanca = "INSERT INTO usuarios (usu_email, usu_nome, usu_senha, usu_tipo, usu_status) 
                VALUES ('caixa@gmail.com', 'Balança', ?, 'balanca', 'S')";
$stmt = $conn->prepare($sql_balanca);
$stmt->bind_param("s", $senha_balanca);
$stmt->execute();

echo "Usuários inseridos com sucesso!";

$stmt->close();  // Fechar o prepared statement
$conn->close();  // Fechar a conexão
?>

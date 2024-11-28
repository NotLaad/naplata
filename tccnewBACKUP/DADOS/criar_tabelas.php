<?php
include 'conexao.php';  // Conectar com o banco de dados

// Criar a tabela Fichas
$sql_fichas = "CREATE TABLE IF NOT EXISTS Fichas (
    Fic_cod INT PRIMARY KEY NOT NULL,
    Fic_ativo_sn ENUM ('S','N') NOT NULL DEFAULT 'S',
    Fic_valor_calculado DECIMAL(10, 2) DEFAULT NULL,
    Fic_valor_inicial DECIMAL(10, 2) DEFAULT NULL,  -- Coluna para o valor inicial da ficha
    Fic_dt_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Fic_dt_desativacao DATETIME DEFAULT NULL,
    Fic_desc_desativacao TEXT NULL
)";
if ($conn->query($sql_fichas) === TRUE) {
    echo "Tabela Fichas criada com sucesso<br>";
} else {
    echo "Erro ao criar tabela Fichas: " . $conn->error . "<br>";
}

// Criar a tabela Precos
$sql_precos = "CREATE TABLE IF NOT EXISTS Precos (
    Grp_precos INT AUTO_INCREMENT PRIMARY KEY,
    Grp_desc VARCHAR(50) NOT NULL,
    Grp_unidade_de_medida ENUM('un', 'kg', 'l') NOT NULL,
    Grp_Valor DECIMAL(10, 2) NOT NULL,
    Grp_Ativo_SN CHAR(1) NOT NULL CHECK (Grp_Ativo_SN in ('S', 'N'))
)";
if ($conn->query($sql_precos) === TRUE) {
    echo "Tabela Preços criada com sucesso<br>";
} else {
    echo "Erro ao criar tabela Preços: " . $conn->error . "<br>";
}

// Criar a tabela Balanca
$sql_balanca = "CREATE TABLE IF NOT EXISTS Balanca (
    Fic_cod INT NOT NULL,
    Bal_data DATETIME NULL,
    Bal_id_contador INT NOT NULL,
    Grp_cod INT NOT NULL,
    Bal_peso DECIMAL(10, 2) NOT NULL,
    Bal_Valor DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (Fic_cod) REFERENCES Fichas(Fic_cod),
    FOREIGN KEY (Grp_cod) REFERENCES Precos(Grp_precos)
)";
if ($conn->query($sql_balanca) === TRUE) {
    echo "Tabela Balança criada com sucesso<br>";
} else {
    echo "Erro ao criar tabela Balança: " . $conn->error . "<br>";
}

// Criar a tabela Relatório
$sql_relatorio = "CREATE TABLE Relatorio_Vendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ficha_cod INT,
    valor_ficha DECIMAL(10,2),
    valor_pago DECIMAL(10,2),
    troco DECIMAL(10,2),
    forma_pagamento VARCHAR(20),
    data_compra DATETIME
)";
if ($conn->query($sql_relatorio) === TRUE) {
    echo "Tabela Relatório de Vendas criada com sucesso<br>";
} else {
    echo "Erro ao criar tabela Relatório de Vendas: " . $conn->error . "<br>";
}

// Criar a tabela Usuarios
$sql_usuarios = "CREATE TABLE IF NOT EXISTS usuarios (
    usu_id INT AUTO_INCREMENT PRIMARY KEY,
    usu_nome VARCHAR(100) NOT NULL,
    usu_email VARCHAR(60) NOT NULL UNIQUE,
    usu_senha VARCHAR(255) NOT NULL,
    usu_tipo VARCHAR(20) NOT NULL,  
    usu_status ENUM ('S', 'N') NOT NULL DEFAULT 'S'
)";
if ($conn->query($sql_usuarios) === TRUE) {
    echo "Tabela Usuarios criada com sucesso<br>";
} else {
    echo "Erro ao criar tabela Usuarios: " . $conn->error . "<br>";
}

// Criar a tabela Configuracao
$sql_configuracao = "CREATE TABLE IF NOT EXISTS Configuracao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    valor_ficha DECIMAL(10, 2) NOT NULL DEFAULT 10.00
)";
if ($conn->query($sql_configuracao) === TRUE) {
    echo "Tabela Configuração criada com sucesso<br>";
} else {
    echo "Erro ao criar tabela Configuração: " . $conn->error . "<br>";
}

// Inserir valor inicial na tabela Configuracao (se necessário)
$sql_valor_inicial = "INSERT IGNORE INTO Configuracao (valor_ficha) VALUES (10.00)";
if ($conn->query($sql_valor_inicial) === TRUE) {
    echo "Valor inicial para fichas inserido com sucesso<br>";
} else {
    echo "Erro ao inserir valor inicial: " . $conn->error . "<br>";
}

$conn->close();  // Fechar a conexão
?>

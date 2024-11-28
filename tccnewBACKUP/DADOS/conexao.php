<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tccnew";

// Criando a conexão com o servidor MySQL (sem selecionar o banco ainda)
$conn = new mysqli($servername, $username, $password);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o banco de dados existe, se não, criar
$sql_db = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql_db) === TRUE) 
// Selecionando o banco de dados
$conn->select_db($dbname);


?>

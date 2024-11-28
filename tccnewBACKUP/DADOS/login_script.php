<?php
// Incluindo o arquivo de conexão com o banco de dados
$path = realpath('../DADOS/conexao.php'); // Caminho relativo para o arquivo conexao.php
if ($path === false) {
    die("Erro: O arquivo conexao.php não foi encontrado! Verifique o caminho.");
} else {
    include $path;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coletando os dados do formulário de login
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifique se a conexão foi bem-sucedida e use o $conn (mysqli) para preparar a consulta
    if ($conn) {
        try {
            // Preparando a consulta para buscar o usuário pelo email
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usu_email = ? LIMIT 1");
            $stmt->bind_param("s", $email); // "s" para string
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                // Verifique a senha usando password_verify
                if (password_verify($senha, $user['usu_senha'])) {
                    // Iniciar a sessão e armazenar os dados do usuário
                    session_start();
                    $_SESSION['usu_id'] = $user['usu_id'];
                    $_SESSION['usu_nome'] = $user['usu_nome'];
                    $_SESSION['usu_tipo'] = $user['usu_tipo'];

                    // Redirecionar para a página de acordo com o tipo de usuário
                    if ($user['usu_tipo'] == 'adm') {
                        header("Location: ../CARDS/index_adm.php");
                    } elseif ($user['usu_tipo'] == 'funcionario') {
                        header("Location: ../CARDS/balanca.php");
                    } elseif ($user['usu_tipo'] == 'balanca') {
                        header("Location: ../CARDS/caixa.php");
                    }
                    exit; // Finaliza o script após o redirecionamento
                } else {
                    echo "Senha incorreta!";
                }
            } else {
                echo "Usuário não encontrado!";
            }

            // Fechar a declaração
            $stmt->close();
        } catch (Exception $e) {
            echo "Erro ao realizar consulta: " . $e->getMessage();
        }
    } else {
        echo "Erro na conexão com o banco de dados!";
    }
}
?>

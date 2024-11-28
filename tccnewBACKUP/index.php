<?php
require_once __DIR__ . '/DADOS/conexao.php'; // CONEXÃO COM O BANCO
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café da Marilsa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
<header class="bordered">
    <div class="inicio-container">
        <img src="UPLOAD/menu.png" alt="Ícone Início" class="inicio-icon">
        <span class="inicio-text">INÍCIO</span>
    </div>
    <img src="UPLOAD/naplata_preto.png" alt="Logo" class="logo">
</header>


    
  

   <!-- Título "Entrar Como" -->
<div class="container text-center my-2">
    <h1 class="entrar">Entrar Como:</h1>
</div>


    <!-- Cards -->
    <section class="cards-section container my-3">
        <div class="row row-cols-1 row-cols-md-3 g-2">
            <div class="col">
                <div class="card" data-bs-toggle="modal" data-bs-target="#loginModal" data-email="adm@gmail.com">
                    <img src="UPLOAD/administrador.png" class="card-img-top" alt="Administrador">
                    <div class="card-body">
                        <h5 class="card-title">Administrador</h5>
                        <p class="card-description">Acesso para registro de produtos e fichas.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card" data-bs-toggle="modal" data-bs-target="#loginModal" data-email="funcionario@gmail.com">
                    <img src="UPLOAD/bl.png" class="card-img-top" alt="Sistema Balança">
                    <div class="card-body">
                        <h5 class="card-title">Sistema Balança</h5>
                        <p class="card-description">Acesso ao Sistema da Balança.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card" data-bs-toggle="modal" data-bs-target="#loginModal" data-email="caixa@gmail.com">
                    <img src="UPLOAD/cx1.png" class="card-img-top" alt="Sistema Caixa">
                    <div class="card-body">
                        <h5 class="card-title">Sistema Caixa</h5>
                        <p class="card-description">Acesso ao Sistema do Caixa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal de Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" method="post" action="DADOS/login_script.php">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" required readonly>

                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" required>

                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Script para preencher o campo de email no modal conforme o card clicado
        const modal = document.getElementById('loginModal');
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // O card clicado
            const email = button.getAttribute('data-email'); // Obtém o email atribuído ao card
            const emailInput = modal.querySelector('#email'); // Seleciona o campo de email
            emailInput.value = email; // Define o email no campo
        });
    </script>
</body>
</html>

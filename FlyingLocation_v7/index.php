<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página inicial</title>

    <link rel="stylesheet" href="assets/bootstrap-5.3.3-dist/css/bootstrap.css">

    <style>
        .homepage{
            margin: 1rem;
        }
    </style>
</head>
    <body>

        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">FlyingLocation</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="">Inicio</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="tela/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="tela/cadastro.php">Cadastrar-se</a>
                    </li>
                </ul>
                </div>
            </div>
        </nav>

        <h1 class="homepage">Bem-vindo!</h1> 
        <h5 class = "homepage">Estamos felizes que tenha nos visitado. O nosso site tem o propósito de venda de qualquer tipo de objeto</h5>
        <p class = "homepage">Para ter acesso às compras da loja, faça login. </p>
        

    
    <script src="assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    </body>
</html>

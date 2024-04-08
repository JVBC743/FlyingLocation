<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    require("../Adaptador/BDAcesso.php");// Dessa forma está correta.
    use padroes_projeto\Adaptador\BDAcesso;
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/basic.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
             <a class="navbar-brand" href="../index.php">FlyingLocation</a>
             <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
             </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="../index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="cadastro.php">Cadastrar-se</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="Mensagem-Inicial center">
            <h4>Criar conta</h4> 
    </div>
    <div class="center">
       
        <form action = "<?php echo $_SERVER["PHP_SELF"];?>" method = "post">
    
            <div class="input-group mb3" id="width-30rem">
                <span class="input-group-text" id="basic-addon1">Nome</span>
                <input type="text" class="form-control" name = "nome_cadastro" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <br>
            <div class="input-group mb3" id="width-30rem">
                <span class="input-group-text" id="basic-addon1">CEP (sem o traço)</span>
                <input type="text" class="form-control" name = "cep_cadastro" placeholder="14234101" aria-label="14234101" aria-describedby="basic-addon1">
            </div>
            <br>
            <div class="input-group mb3" id="width-30rem">
                <span class="input-group-text" id="basic-addon1">Senha</span>
                <input type="password" class="form-control" name = "senha_cadastro" placeholder="12345678" aria-label="Password" aria-describedby="basic-addon1">
            </div>
            <br>
            <div class="input-group mb3" id="width-30rem">
            <span class="input-group-text" id="basic-addon1">N° da casa: </span>
            <input type = "text" class="form-control" name = "numeracao_casa" aria-label="0101010" aria-describedby="basic-addon1">
            </div>
    
            <br>
    
            <div class="center-item">

                <input class="btn btn-primary" type = "submit" name = "botao_cadastro1">

            </div>

        
        </form>
    </div>

    
    <script src="assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>

<?php

    if(isset($_POST["botao_cadastro1"])){
        if(isset($_POST["nome_cadastro"]) && !empty($_POST["nome_cadastro"])){

            $nome_cadastro = $_POST["nome_cadastro"];

            if(isset($_POST["senha_cadastro"]) && !empty($_POST["senha_cadastro"])){

                $senha_cadastro = $_POST["senha_cadastro"];

                if (isset($_POST["cep_cadastro"]) && !empty($_POST["cep_cadastro"])){

                    $cep_cadastro = $_POST["cep_cadastro"];

                    if(isset($_POST["numeracao_casa"]) && !empty($_POST["numeracao_casa"])){

                        $numeracao = $_POST["numeracao_casa"];

                        if (preg_match('/^\d{8}$/', $cep_cadastro)) {
                        //Olha
                        // $sql = "INSERT INTO Pessoas (nomePessoa, senhaPessoa, cepPessoa, numeracao)
                        // VALUES ('$nome_cadastro','$senha_cadastro', '$cep_cadastro', '$numeracao');";

                        // $resultado = mysqli_query($conexao, $sql);

                        $banco = BDAcesso::getInstance();
                        $banco->inserirDados("Pessoas", "'$nome_cadastro', '$senha_cadastro', '$cep_cadastro', '$numecao", "nomePessoa, senhaPessoa, cepPessoa, numeracao");
                        
                        

                        echo "Dados cadastrados!";

                        sleep(3);

                        header("Location: login.php");

                        }else{
                            echo "Numeração inválida ou não inserida";
                        }
                }else{
                    echo "CEP inválido";
                }
            }else{
                echo "Senha não inserida";
            }
        }else{
            echo "Nome não inserido";
        }
    }
}
?>
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
    <title>Login</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/basic.css">

    <style>
        #width-30rem{
            width: 30rem;
        }
    </style>
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
                        <a class="nav-link active" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro.php">Cadastrar-se</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <h4 class="center">Fazer Login</h4>
    <div class="center">
    
        <form action = "<?php echo $_SERVER["PHP_SELF"];?>" method = "post">
    
            <div class="input-group mb-3" id="width-30rem">
                <span class="input-group-text" id="basic-addon1">Nome</span>
                <input type="text" class="form-control" name = "nome_usuario" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3" id="width-30rem">
            <span class="input-group-text" id="basic-addon1">Senha</span>
                <input type="password" class="form-control" name = "senha_usuario" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
            </div>
            <div class="center-item">
                <input type = "submit" value = "Entrar" name = "botao_login" class="btn btn-primary">
            </div>
        </form>
    </div>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>
<?php 
    


    if(isset($_POST["botao_login"])){
        if(isset($_POST["nome_usuario"])){

            $nome_usuario = $_POST["nome_usuario"];

            if(isset($_POST["senha_usuario"])){

                $senha_usuario = $_POST["senha_usuario"];

                $banco = BDAcesso::getInstance();
                $retorno = $banco->buscaSQL("*","Pessoas", "WHERE", "nomePessoa = '$nome_usuario'");

                if($retorno && mysqli_num_rows($retorno) > 0){

                    $linha = mysqli_fetch_assoc($retorno);
                    $senha_armazenada = $linha["senhaPessoa"];

                    if($senha_usuario == $senha_armazenada){

                    $_SESSION["nome_usuario"] = $nome_usuario;
                    
                    header("Location: ../TelaLoja/loja.php");

                    exit();
                    }else{
                        echo "A senha não bate";
                    }
                }else{
                    echo "<br> O usuário não foi encontrado";
                }
            }else{
                echo "Insira a senha";
            }
        }else{
            echo "Insira o nome de usuário.";
        }
    }
?>
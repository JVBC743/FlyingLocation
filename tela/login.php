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
    <link rel="stylesheet" href="../assets/css/login_register.css">

</head>

<body>
    
<div class="container">
        <div class="system-name">
            <p>Flying
                <span><br>Location</span></p>
        </div>
        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
            <div class="login">
                <label class="form-label">Nome de Usuário</label>
                <input class="form-control" name = "email" type="text" placeholder="E-mail">
                <label class="form-label">Senha</label>
                <input class="form-control" name = "senha_usuario" type="password" placeholder="Senha"><br>
                <div class="inline">
                    <input type = "submit" value = "Logar" name = "botao_login" class="btn btn-success">
                    <a type="button" href="cadastro.php" class="btn btn-primary">Cadastrar-se</a>
                </div>
                <br>
                <a href="recuperacao_senha.php">Esqueci a senha</a>
            </div>
        </form>
</div>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>
<?php 
    
    if(isset($_POST["botao_login"])){
        if(isset($_POST["email"])){

            $email = $_POST["email"];

            if(isset($_POST["senha_usuario"])){

                $senha_usuario = $_POST["senha_usuario"];

                $banco = BDAcesso::getInstance();
                $retorno = $banco->buscaSQL("*","usuarios", "WHERE", "email = '$email'");

                if($retorno && mysqli_num_rows($retorno) > 0){

                    $linha = mysqli_fetch_assoc($retorno);
                    $senha_armazenada = $linha["senhaPessoa"];

                    if($senha_usuario == $senha_armazenada){

                    $_SESSION["email"] = $email;
                    
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
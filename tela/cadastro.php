<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    require '../email/vendor/autoload.php';
    require("../Adaptador/BDAcesso.php");// Dessa forma está correta.
    require("../classes/Email.php");
    use padroes_projeto\Adaptador\BDAcesso;
    use PHPMailer\PHPMailer\PHPMailer;
    use email\Email;

    //$mail = new PHPMailer(true);

    $envio_email = new Email();


    $anonimo = "anonimo.png";
    $caminho = "assets/img/users/";

    $banco = BDAcesso::getInstance();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/login_register.css">
</head>
<body>
    
<div class="container">
    <div class="system-name-register">
        <p>Flying
            <span><br>Location</span></p>
    </div>
    <form action = "<?php echo $_SERVER["PHP_SELF"];?>" method = "post">
        <div class="register">
            <label class="form-label">Nome de Usuário</label>
            <input class="form-control" name = "nome_cadastro" type="text" placeholder="Username">
            <label class="form-label">CEP</label>
            <input class="form-control" name = "cep_cadastro" type="text" placeholder="10000787">
            <label class="form-label">E-mail</label>
            <input class="form-control" name = "email" type="email" placeholder="exemple@exemple.com">
            <label class="form-label">N° Casa</label>
            <input class="form-control" name = "numeracao_casa" type="number" placeholder="0101">
            <label class="form-label">Senha</label>
            <input class="form-control" name = "senha_cadastro" type="password" placeholder="password"><br>
            <div class="inline">
                <input type = "submit" value = "Cadastrar" name = "botao_cadastro1" class="btn btn-success">
                <a type="button" href="login.php" class="btn btn-primary">Logar-se</a>
            </div>
        </div>
    </form>
</div>    
    <script src="assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>

<?php

    if(isset($_POST["botao_cadastro1"])){

        echo "DISGRAÇAAAAA";

        if(isset($_POST["nome_cadastro"]) && !empty($_POST["nome_cadastro"])){

            $nome_cadastro = $_POST["nome_cadastro"];

            if(isset($_POST["senha_cadastro"]) && !empty($_POST["senha_cadastro"])){

                $senha_cadastro = $_POST["senha_cadastro"];

                if (isset($_POST["cep_cadastro"]) && !empty($_POST["cep_cadastro"])){        

                    $cep_cadastro = $_POST["cep_cadastro"];

                    if(isset($_POST["email"]) && !empty($_POST["email"])){

                        $email = $_POST["email"];

                            if(isset($_POST["numeracao_casa"]) && !empty($_POST["numeracao_casa"])){

                                $numero_casa = $_POST["numeracao_casa"];

                                //echo "<script>window.alert('Cheguei aqui1')</script>";


                            if (preg_match('/^\d{8}$/', $cep_cadastro)) {

                                $inserir_usr = $banco->inserirDados("usuarios_temporarios", "'$nome_cadastro', '$senha_cadastro', '$cep_cadastro', '$numero_casa', '$email', NOW() + INTERVAL 1 HOUR", "nome, senha, cep, numero_casa, email, data_expiracao");
                                
                                if($inserir_usr == false){
                                    
                                    echo "<script>window.alert('Houve um erro na inserção dos dados no banco temporario')</script>";
                                
                                }else{

                                    $assunto = "Criação de uma conta no site FlyingLocation";
                                    
                                    $conteudo = "Olá, foi solicitado a criação de uma conta no nosso site FlyingLocation.<br> 
                                    Caso não tenha solicitado nenhuma abertuda de conta no nosso site, você pode ignorar este e-mail.
                                    Para criar a sua conta, você pode clicar no link a seguir: ";

                                    $alt = "Olá, foi solicitado a criação de uma conta no nosso site FlyingLocation.<br> 
                                    Caso não tenha solicitado nenhuma abertuda de conta no nosso site, você pode ignorar este e-mail.
                                    Para criar a sua conta, você pode clicar no link a seguir: ";

                                    //echo "<script>window.alert('Cheguei aqui2')</script>";

                                    $envio = $envio_email->emailGenerico($assunto, $conteudo, $alt, $email, "usuario");

                                    //echo "<script>window.alert('Cheguei aqui3')</script>";

                                    if($envio == false){

                                        echo "<script>window.alert('Houve um erro no envio do e-mail')</script>";

                                    }else{

                                        $_SESSION["aviso"] = true;
                                        header("Location: ../index.php");

                                    }
                                }   
                            
                            }else{

                                echo "<script>window.alert('É o bengas?')</script>";

                            }

                        }else{

                            echo "Numeração inválida ou não inserida";
                            echo "<script>window.alert('Numeração inválida ou não inserida')</script>";
                        }
                        
                    }else{
                        echo "CEP inválido";
                        echo "<script>window.alert('CEP inválido')</script>";

                    }

                }else{

                    echo "E-mail não inserido.";
                    echo "<script>window.alert('E-mail não inserido')</script>";

                }
            }else{
                echo "Senha não inserida";
                echo "<script>window.alert('Senha não inserida')</script>";

            }
        }else{
            echo "Nome não inserido";
            echo "<script>window.alert('Nome não inserido')</script>";

        }
    }
    
?>
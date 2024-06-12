<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    require '../email/vendor/autoload.php';
    require("../Adaptador/BDAcesso.php");// Dessa forma está correta.
    use padroes_projeto\Adaptador\BDAcesso;
    use PHPMailer\PHPMailer\PHPMailer;

    $mail = new PHPMailer(true);

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

                                $numeracao = $_POST["numeracao_casa"];

                                if (preg_match('/^\d{8}$/', $cep_cadastro)) {

                                $token = uniqid();

                                $inserir_usr = $banco->inserirDados("UsuariosTemporarios", "'$nome_cadastro', '$senha_cadastro', '$cep_cadastro', '$numeracao', '$email', NOW() + INTERVAL 1 HOUR", "nomePessoa, senhaPessoa, cepPessoa, numeracao, email, data_expiracao");

                                if($inserir_usr == false){

                                    echo "<script>alert('Inserção inválida')</script>";


                                }else{
                                    
                                    $data_expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));

                                    $inserir_tok = $banco->inserirDados("Tokens", "'$token', '$data_expiracao', '$email'", "valorToken, dataExpiracao, email");
                                    
                                    if($inserir_tok == false){

                                        echo "<script>alert('No token, não funfou.')</script>";

                                    }else{


                                        $mail->isSMTP();
                                        $mail->Host = 'smtp.office365.com';
                                        $mail->SMTPAuth = true;
                                        $mail->SMTPSecure = 'tls';
                                        $mail->Username = 'flyinglocation2902@outlook.com';
                                        $mail->Password = 'FlyLoc@2902';
                                        $mail->Port = 587;

                                        $mail->setFrom('flyinglocation2902@outlook.com', 'JVBC/JCMS Co.');
                                        $mail->addAddress($email);

                                        $mail->isHTML(true);

                                        $mail->Subject = "Criação de conta - FlyingLocation";

                                        $link_cadastro = 'http://201.2.18.191:1234/padroes_projeto/tela/pegar_token.php?token=' . $token;

                                        $mail->Body = nl2br("Olá, foi solicitado a criação de uma conta no nosso site FlyingLocation.<br> 
                                        Para criar a sua conta, você pode clicar no link a seguir: <a href = '$link_cadastro'>clique aqui</a><br><br>
                                        Caso não tenha solicitado nenhuma abertuda de conta no nosso site, você pode ignorar este e-mail.");

                                        $mail->AltBody = nl2br(strip_tags("Criação de Conta."));

                                            if(!$mail->send()) {
                                                echo 'Não foi possível enviar a mensagem.';
                                                echo 'Erro: ' . $mail->ErrorInfo;
                                            } else {
                                                echo "E-mail enviado com sucesso!!!<br>";
                                            }

                                        echo "<script>alert('E-mail enviado com sucesso!')</script>";


                                        $_SESSION["aviso"] = "yes";
                                        header("Location: ../index.php");
                                    }
                                }

                            }else{

                                echo "Numeração inválida ou não inserida";
                                echo "<script>alert('Numeração inválida ou não inserida')</script>";
                            }
                        }
                        

                    }else{
                        echo "CEP inválido";
                        echo "<script>alert('CEP inválido')</script>";

                    }

                }else{

                    echo "E-mail não inserido.";
                    echo "<script>alert('E-mail não inserido')</script>";

                }
            }else{
                echo "Senha não inserida";
                echo "<script>alert('Senha não inserida')</script>";

            }
        }else{
            echo "Nome não inserido";
            echo "<script>alert('Nome não inserido')</script>";

        }
    }
?>
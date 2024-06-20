<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require '../email/vendor/autoload.php';
    require '../Adaptador/BDAcesso.php';// Dessa forma está correta.
    
    use padroes_projeto\Adaptador\BDAcesso;
    use PHPMailer\PHPMailer\PHPMailer;

    $banco_rec = BDAcesso::getInstance();

    $mail = new PHPMailer(true);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/login_register.css">
    <link rel="icon" type = "image/jpeg" href="../assets/img/personalizacao/logo_diminuida.jpeg">

    <style>
        #width-30rem{
            width: 30rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="mensagem">
            <p>Olá, vejo que se esqueceu de sua senha.</p>
            <p>É realmente uma pena que isso esteja acontecendo com você,<br> mas garantiremos que tudo se resolva da melhor forma</p>
            <p>Insira um e-mail para a recuperação de senha no campo abaixo.</p>
        </div>
        
            <form action = "<?php echo $_SERVER["PHP_SELF"]; ?>" method = "post">
                <div class="recuperacao-form">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="width-30rem" name = "email" id="exampleFormControlInput1" placeholder="Insira aqui o seu e-mail para a recuperação" placeholder="name@example.com">
                    </div>   
                    <div>
                        <input type = "submit" class="btn btn-success" name = "botao_email">
                        <a href="../index.php" class="btn btn-primary">Voltar</a>
                    </div>
                </div>
            </form>
        </div>
        <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>

<?php
    if(isset($_POST["botao_email"])){
        if(isset($_POST["email"]) && !empty($_POST["email"])){
            
            $email = $_POST["email"];

            $resultado = $banco_rec->buscaSQL("*","usuarios", "WHERE", "email = '$email'");

            if($resultado && mysqli_num_rows($resultado)){

                $linha = mysqli_fetch_assoc($resultado);
                $email_verificado = $linha["email"];
                $id_usuario = $linha["id"];

                $token = uniqid();

                $insercao = $banco_rec->inserirDados("tokens", "'$id_usuario', '$token', NOW() + INTERVAL 1 HOUR", "id_usuario, valor_token, data_expiracao");

                if ($insercao) {

                    echo "Token gerado com sucesso!<br>";

                } else {

                    echo "Deu ruim no Token";

                }

                echo "O seu e-mail está cadastrado no sistema!<br>";

                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com';
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'tls';
                $mail->Username = 'flyinglocation2902@outlook.com';
                $mail->Password = 'FlyLoc@2902';
                $mail->Port = 587;

                $mail->setFrom('flyinglocation2902@outlook.com', 'JVBC/JCMS Co.');
                $mail->addAddress($email_verificado);

                $mail->isHTML(true);

                $mail->Subject = "Alteração de senha - FlyingLocation";

                $link_recuperacao = 'http://201.2.18.191:2222/FlyingLocation/tela/pegar_token.php?token=' . $token;

                $mail->Body = nl2br("Olá, foi solicitado a mudança de senha para a conta que possui este e-mail vinculado no nosso site FlyingLocation. <br> 
                Se você não solicitou nenhum tipo de recuperação de senha para esta conta, por favor, ignore este e-mail. <br><br>
                Caso queira alterar a sua senha, <a href = '$link_recuperacao'>clique aqui</a>");

                $mail->AltBody = nl2br(strip_tags("Mudança de senha."));

                if(!$mail->send()) {
                    echo 'Não foi possível enviar a mensagem.';
                    echo 'Erro: ' . $mail->ErrorInfo;
                } else {
                    echo "E-mail enviado com sucesso!!!<br>";
                }

            }else{
                echo "O seu e-mail não foi encontrado no banco de dados.";
            }
        }else{
            echo "E-mail não inserido";
        }
    }
?>


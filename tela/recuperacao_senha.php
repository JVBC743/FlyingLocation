<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require '../email/vendor/autoload.php';
    require '../Adaptador/BDAcesso.php';// Dessa forma está correta.
    require ("../classes/Email.php");

    
    use padroes_projeto\Adaptador\BDAcesso;
    use PHPMailer\PHPMailer\PHPMailer;
    use email\Email;

    $banco_rec = BDAcesso::getInstance();

    $envio_email = new Email();

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

                    echo "<script>window.alert('Token gerado com sucesso!')</script>";

                } else {

                    echo "<script>window.alert('Deu ruim no token')</script>";

                }

                echo "O seu e-mail está cadastrado no sistema!<br>";

                $assunto = "Alteração de senha - FlyingLocation";

                $conteudo = "Olá, foi solicitado a mudança de senha para a conta que possui este e-mail vinculado no nosso site FlyingLocation. <br> 
                Se você não solicitou nenhum tipo de recuperação de senha para esta conta, por favor, ignore este e-mail. <br><br>
                Caso queira alterar a sua senha, ";

                $alt = "Olá, foi solicitado a mudança de senha para a conta que possui este e-mail vinculado no nosso site FlyingLocation. <br> 
                Se você não solicitou nenhum tipo de recuperação de senha para esta conta, por favor, ignore este e-mail. <br><br>
                Caso queira alterar a sua senha, ";

                echo "<script>window.alert('Cheguei aqui1')</script>";

                $envio = $envio_email->emailGenerico($assunto, $conteudo, $alt, $email, "token", $id_usuario);

                echo "<script>window.alert('Cheguei aqui2')</script>";

                if($envio == false){

                    echo "<script>window.alert('Houve um erro no envio do e-mail')</script>";

                }else{

                    echo "E-mail enviado com sucesso!";
                }

            }else{
                echo "<script>window.alert('E-mail não encontrado no banco de dados')</script>";
            }
        }else{
            echo "<script>window.alert('E-mail não inserido')</script>";
        }
    }
?>


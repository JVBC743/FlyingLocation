<?php

    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require '../email/vendor/autoload.php';
    require '../Adaptador/BDAcesso.php';// Dessa forma está correta.

    use padroes_projeto\Adaptador\BDAcesso;
    use PHPMailer\PHPMailer\PHPMailer;

    
    $banco_rec = BDAcesso::getInstance();

    $id_usuario = null;
    $token_adquirido = null;
    $mail = new PHPMailer(true);

    $token_adquirido = isset($_GET["token"]) ? $_GET["token"] : null;

    echo $token_adquirido;

    if ($token_adquirido == null) {

        echo "O valor do token é nulo.";
        exit;

    }else{

        $busca_token = $banco_rec->buscaSQL("*", "tokens", "WHERE", "valor_token = '$token_adquirido'");

        if($busca_token && mysqli_num_rows($busca_token) > 0){

            $linha_token = mysqli_fetch_assoc($busca_token);

            $token_vindo_busca = $linha_token["valor_token"];

            $email = $linha_token["email"];

            if($email == null){

                $id_usuario = $linha_token["id_usuario"];

                $_SESSION["token_banco"] = $token_vindo_busca;
                $token_sessao = $_SESSION["token_banco"];

            }else{

                $busca_email = $banco_rec->buscaSQL("*", "usuarios_temporarios", "WHERE", "email = '$email'");

                if($busca_email && mysqli_num_rows($busca_email)){

                    $linha_email = mysqli_fetch_assoc($busca_email);
                    
                    $email = $linha_email["email"];

                    $_SESSION["email_temp"] = $email;

                    echo "Cheguei aqui 1";
                    //sleep(10);

                    header("Location: tela_recuperacao.php");
                }
            }

            echo "Token vindo da busca no banco foi um sucesso!";

        }else{

            echo "Busca do token do banco mal sucedida.";

        }
    }

    if($id_usuario != null){

        $busca_usuario = $banco_rec->buscaSQL("*", "usuarios", "WHERE", "id = $id_usuario");

        if($busca_usuario && mysqli_num_rows($busca_usuario) > 0){

            $linha_usr = mysqli_fetch_assoc($busca_usuario);

            $nome_usuario = $linha_usr["nome"];

            $_SESSION["nome_usuario"] = $nome_usuario;

            $nome_usuario_session = $_SESSION["nome_usuario"];

            echo "O session do nome do usuario foi passado";

        }else{

            echo "Busca do id mal sucedida";

        }


    }

    if($nome_usuario_session != null && $token_sessao != null){

        echo "Cheguei aqui 2";
        //sleep(10);

        header("Location: tela_recuperacao.php");

    }





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

        $busca_token = $banco_rec->buscaSQL("*", "Tokens", "WHERE", "valorToken = '$token_adquirido'");

        if($busca_token && mysqli_num_rows($busca_token) > 0){

            $linha_token = mysqli_fetch_assoc($busca_token);

            $token_vindo_busca = $linha_token["valorToken"];

            $email = $linha_token["email"];

            $id_usuario = $linha_token["idUsuario"];

            $_SESSION["token_banco"] = $token_vindo_busca;

            $token_sessao = $_SESSION["token_banco"];

            echo "Token vindo da busca no banco foi um sucesso!";

        }else{

            echo "Busca do token do banco mal sucedida.";

        }
    }

    $busca_email = $banco_rec->buscaSQL("*", "UsuariosTemporarios", "WHERE", "email = '$email'");

    if($busca_email && mysqli_num_rows($busca_email)){
        $linha_email = mysqli_fetch_assoc($busca_email);
        
        $nome_usuario = $linha_email["nomePessoa"];

        $_SESSION["nome_usuario"] = $nome_usuario;
        header("Location: tela_recuperacao.php");
    }

    $busca_usuario = $banco_rec->buscaSQL("*", "Pessoas", "WHERE", "id = $id_usuario");

    if($busca_usuario && mysqli_num_rows($busca_usuario) > 0){

        $linha_usr = mysqli_fetch_assoc($busca_usuario);

        $nome_usuario = $linha_usr["nomePessoa"];

        $_SESSION["nome_usuario"] = $nome_usuario;

        $nome_usuario_session = $_SESSION["nome_usuario"];

        echo "O session do nome do usuario foi passado";

    }else{

        echo "Busca do id mal sucedida";

    }
    // echo "<br><br>" .$nome_usuario_session . "<br>";
    // echo $token_sessao;

    if($nome_usuario_session != null && $token_sessao != null){

        header("Location: tela_recuperacao.php");

    }





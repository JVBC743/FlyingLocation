<?php

    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // require '../email/vendor/autoload.php';
    require '../Adaptador/BDAcesso.php';// Dessa forma está correta.

    use padroes_projeto\Adaptador\BDAcesso;
    // use PHPMailer\PHPMailer\PHPMailer;

    $banco = BDAcesso::getInstance();

    $nome_usuario = $_SESSION["nome_usuario"];

    if($_SESSION["nome_usuario"] === null){

        $nome_usuario = $_SESSION["nome_usuario"];

    }

    $token = $_SESSION["token_banco"];

    if($nome_usuario == null){

        echo "O nome do usuário está nulo.";
    }
    if($token == null){

        echo "O token do usuário está nulo.";
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperando a senha...</title>
</head>
<body>

    <?php if($nome_usuario != null): ?>

        <?php 
            
            $busca_usuario = $banco->buscaSQL("*","UsuariosTemporarios");

            if($busca_usuario && mysqli_num_rows($busca_usuario)){
                
                $linha = mysqli_fetch_assoc($busca_usuario);

                $nome_usuario = $linha["nome"];
                $senha = $linha["senha"];
                $cep = $linha["cep"];
                $numero_casa = $linha["numero_casa"];
                $email = $linha["email"];
                $imagem_pessoa = $linha["imagem_pessoa"];
                
            }

            $banco->inserirDados("usuarios", "'$nome_usuario', '$senha', '$cep', '$numero_casa', '$email' , '$imagem_pessoa'", "nome, senha, cep, numero_casa, email, imagem_pessoa");
            
            echo "<h1> A sua conta foi cadastrada com sucesso!</h1>";
            
            sleep(8); 
            
            header("Location: login.php");
        
        ?>

    <?php else: ?>

    <h1>Olá, <?php echo $nome_usuario; ?></h1>

    <h3>Insira, agora, a sua nova senha: </h3>

    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <input type="hidden" name="token" value="<?php echo $token_session; ?>">
        <input type="text" name="mudar_senha1">
        <br>
        <h3>Insira a senha novamente:</h3>
        <input type="text" name="mudar_senha2">
        <br>
        <input type="submit" name="botao_mudar_senha">
    </form>
    
    <?php   
        if(isset($_POST["botao_mudar_senha"])){
            if(isset($_POST["mudar_senha1"]) && !empty($_POST["mudar_senha1"])){
                if(isset($_POST["mudar_senha2"]) && !empty($_POST["mudar_senha2"])){
                    $valor_senha_1 = $_POST["mudar_senha1"];
                    $valor_senha_2 = $_POST["mudar_senha2"];

                    if($valor_senha_1 === $valor_senha_2){

                        $alterar_senha = $banco->atualizarDados("usuarios", "senha = '$valor_senha_2'", "WHERE", "nome = '$nome_usuario'");

                        echo "Senha atualizada com sucesso!";

                        header("Location: login.php");

                    }else{
                        echo "As senhas não são iguais.";
                    }
                }else{
                    echo "Segundo campo não inserido";
                }
            }else{

                echo "Primeiro campo não inserido";
            }
        }
    ?>

    <?php endif; ?>
</body>
</html>


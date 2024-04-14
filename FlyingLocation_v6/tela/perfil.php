<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require("../Adaptador/BDAcesso.php");// Dessa forma está correta.
    use padroes_projeto\Adaptador\BDAcesso;

    $banco = BDAcesso::getInstance();

    if(isset($_SESSION["nome_usuario"])){

        $nome_usuario = $_SESSION["nome_usuario"];

    }else{

        $nome_usuario = null;
    }

    $banco = BDAcesso::getInstance();

    $cons = $banco->buscaSQL("*", "Pessoas", "WHERE", "nomePessoa = '$nome_usuario'");
    
    if(mysqli_num_rows($cons) > 0){
        $user_log = mysqli_fetch_assoc($cons);
        
        $caminho = $user_log["caminho"];
        $senha = $user_log["senhaPessoa"];
        $imagem_pss = $user_log["imagem_pessoa"];
        $cargo = $user_log["cargo"];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/basic.css">
</head>
<body>

    <?php if($nome_usuario == null): ?> 

        <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
        <a href = "../index.php">Voltar</a>

    <?php else: ?>

        <img src = "../<?php echo $caminho ."/". $imagem_pss ?>" style = "height: 50px; width: 50px;">

        <h1>Perfil de usuário</h1>

        <form action = "perfil.php" method = "post">

            <div class="input-group mb3" id="width-30rem">
                    <span class="input-group-text" id="basic-addon1">Nome:</span>
                    <input type="text" class="form-control" name = "nome_usuario"  value = "<?php echo $nome_usuario?>" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            
            
            <input type = "submit" class="btn btn-danger" name = "deletar_conta" value = "Excluir Conta">


            <?php if(isset($_POST["deletar_conta"])): ?>

            <h4>Tem a plena CERTEZA de que quer excluir a sua conta? (Essa ação não pode ser desfeita)</h4>

                <input type = "submit" class="btn btn-danger" name = "excluir_ctz" value = "Excluir Mesmo!">

            <?php endif; ?>
            </form>

                <?php 
                    if(isset($_POST["novo_nome"])){
                        $novo_nome = $_POST["novo_nome"];
                        $banco->atualizarDados("Pessoas", "nomePessoa = '$novo_nome'","WHERE", "nomePessoa = '$nome_usuario'");
                        
                        if($banco){

                            echo "Dados atualizados com sucesso!";

                        }else{

                            echo "Falha na atualização.";

                        }
                    }

                    if(isset($_POST["excluir_ctz"])){

                        $resp = $banco->excluirDados("Pessoas", "WHERE", "nomePessoa = '$nome_usuario'");

                        if($resp){

                            header("Location: ../index.php");

                        }else{

                            echo "Conta não excluída.";
                        }
                    }
                ?>
            
    <?php endif; ?>

    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
</body>
</html>
<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require("../Adaptador/BDAcesso.php");
    require("../Adaptador/CepAdaptor.php");

    use padroes_projeto\Adaptador\BDAcesso;

    $nome_usuario = $_SESSION["nome_usuario"];

    if ($_SESSION['nome_usuario'] == null) {
        
        $nome_usuario = $_SESSION["nome_usuario"];

    }

    $banco = BDAcesso::getInstance();
    $resultado_cep = $banco->buscaSQL("*", "usuarios", "WHERE", "nome = '$nome_usuario'");

    if ($resultado_cep && mysqli_num_rows($resultado_cep) > 0) {

        $linha = mysqli_fetch_assoc($resultado_cep);
        $cargo = $linha["cargo"];

    }

    $numero_pessoa = $_SESSION["numero_pessoa"];
    $rua_pessoa = $_SESSION["rua_pessoa"];
    $prod_nome = $_SESSION["prod_nome"];
    $prod_preco = $_SESSION["prod_preco"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação da compra</title>

    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/basic.css">
</head>
<body>

    <?php if($nome_usuario == null): ?>

        <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
        <a href = "../index.php">Voltar</a>

    <?php else: ?>
            <nav class="navbar navbar-expand-lg bg-body-tertiary">  
                <div class="container-fluid">
                    <a class="navbar-brand" href="../index.php">FlyingLocation</a>                 
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="loja.php">Loja</a>
                            </li>

                        <?php if($cargo == 'administrador' || $cargo == 'fornecedor'):?>
                            <li class="nav-item">

                            <form action = "<?php echo $_SERVER["PHP_SELF"];?>" method = "post">
                                <input type = "submit" class="nav-link" value = "Cadastrar Produto" name = "cad_prod">
                            </form>

                            <?php 
                                if(isset($_POST["cad_prod"])){
                                    $_SESSION["nome_usuario"];                                 
                                    header("Location: gerenciar_prod.php");
                                }
                            ?>
                            </li>     
                        <?php endif;?>  

                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href = "editar_prod.php">Meus Produtos</a>
                        </li>

                        </ul>
                        
                        <?php if (!empty($caminho) && !empty($imagem_pss)):?>
                            <div class="d-flex" > 
                            <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img class="me-2" src="../assets/img/users/<?php echo $imagem_pss; ?>" style = "height: 50px; width: 50px;">
                            </button>
                            <form action = "<?php echo $_SERVER["PHP_SELF"]; ?>" method = "post">
                            <ul class="dropdown-menu">
                                <li><input type = "submit" class="dropdown-item" value = "Perfil" name = "perfil"></li>
                                <li><input type = "submit" class="dropdown-item" value = "Sair" name = "sair"></li>
                            </ul>
                            </form>
                                <?php 
                                    if(isset($_POST["perfil"])){
                                        $_SESSION["nome_usuario"];
                                        header("Location: ../tela/perfil.php");
                                    }
                                    if(isset($_POST["sair"])){

                                        header("Location: ../index.php");
                                    }
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>

        <h1>A sua compra foi um sucesso!</h1>

        <h5><?php echo "O produto '$prod_nome' será enviado para a $rua_pessoa no n° $numero_pessoa por R$$prod_preco"; ?></h5>
        <br>

        <h4>Para voltar, clique <a href = "loja.php">aqui</a></h4>

    <?php endif; ?>

    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
    <script src="../assets/js/modals.js"></script>
</body>
</html>
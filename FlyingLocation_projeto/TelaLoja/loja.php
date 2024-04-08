<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require("../Adaptador/BDAcesso.php");// Dessa forma está correta.
    require("../Adaptador/CepAdaptor.php");

    use padroes_projeto\Adaptador\CepAdaptor;
    use padroes_projeto\classes\Cliente;
    use padroes_projeto\Adaptador\BDAcesso;

    $cep_teste = new CepAdaptor();
    $cliente_teste = new Cliente();
    if(isset($_SESSION["nome_usuario"])){
        $nome_usuario = $_SESSION["nome_usuario"];
    }
    else{
        $nome_usuario = null;
    }
    $banco = BDAcesso::getInstance();
    
    $cons = $banco->buscaSQL("*", "Pessoas", "WHERE", "nomePessoa = '$nome_usuario'");
    
    if(mysqli_num_rows($cons) > 0){
        $user_log = mysqli_fetch_assoc($cons);
        $caminho = $user_log["caminho"];
        $imagem_pss = $user_log["imagem_pessoa"];
        $cargo = $user_log["cargo"];
    }
    
    $cons1 = $banco->buscaSQL("cepPessoa","Pessoas","WHERE","nomePessoa = '$nome_usuario'");

    if($cons1 && mysqli_num_rows($cons1) > 0){

        $linha = mysqli_fetch_assoc($cons1);

        $cep = $linha["cepPessoa"];

        $cep_teste->lerCEP($cep);
        $cep_teste->adaptarJson($cliente_teste);
    }

    $cons2 = $banco->buscaSQL("*", "Produtos");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/basic.css">
</head>
<body>

     <?php if($nome_usuario == null): ?> <!--//OLHA O IFFFF-->

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
                            <a class="nav-link active" aria-current="page" >Loja</a>
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
                        <a class="nav-link" aria-current="page" href = "editar_prod.php">Meus Produtos</a>
                    </li>

                        <li class="nav-item">
                            
                            <form action = "loja.php" method = "post">
                                <input type = "submit" name = "logout" class="nav-link" value = "Sair">
                            </form>
                            <?php if(isset($_POST["logout"])) {

                                session_destroy();
                                header("Location: ../tela/login.php");
                                exit();
                            }
                            ?>
                        </li>
                    </ul>
                    <?php if (!empty($caminho) && !empty($imagem_pss)):?>
                        <div class="d-flex">
                            <img class="me-2" src="../<?php echo $caminho .'/'. $imagem_pss; ?>" style = "height: 50px; width: 50px; float: right;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="produtos row">
                <?php if($cons2): ?>
                    <?php $i = 0; while(($linha = mysqli_fetch_assoc($cons2))): ?>
                        <div class="produto col-6 col-md-3 m-3" id="width-20rem" >
                            <img class="img-thumbnail" src="../<?php echo $linha["caminho"]. "/" .$linha["imagem_produto"] ?>" alt="Produto 1">                    
                            <h3><a href="produto.php"><?php echo $linha["nomeProduto"]; ?></a></h3>
                            <p class="preco"><?php echo "R$ " . $linha["precoProduto"]; ?></p>
                            
                            <form action = "<?php echo $_SERVER["PHP_SELF"]; ?>" method = "post">
                                <input type="submit" href="produto.php" class="botao" value="Comprar Agora" name = "comprar<?php echo $i;?>">
                            </form>
                            <?php 
                                if(isset($_POST["comprar".$i])){
                                    $_SESSION["nome_produto"] = $linha["nomeProduto"];
                                    header("Location: produto.php");
                                }
                                $i++;
                            ?>
                        </div>
                    <?php endwhile ;?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <?php
    ?>
</body>
</html>
<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require("Adaptador/BDAcesso.php");// Dessa forma está correta.

    use padroes_projeto\Adaptador\BDAcesso;

    $banco = BDAcesso::getInstance();

    $cons2 = $banco->buscaSQL("*", "Produtos");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página inicial</title>

    <link rel="stylesheet" href="assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/basic.css">

    <style>
        .homepage{
            margin: 1rem;
        }
    </style>
</head>
    <body>


<?php

    function exibirProdutos($consultaSQL)
{
    if ($consultaSQL) {
        $i = 0;
        while (($linha = mysqli_fetch_assoc($consultaSQL))) {
            $preco = str_replace(".",",", $linha["precoProduto"]);

?>
            <div class="produto col-6 col-md-3 m-3" id="width-20rem">
                <img class="img-thumbnail" src="<?php echo $linha["caminho"] . "/" . $linha["imagem_produto"] ?>" alt="Produto 1">
                <h3><a href="produto.php"><?php echo $linha["nomeProduto"]; ?></a></h3>
                <p class="preco"><?php echo "R$ " . $preco; ?></p>

                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <input type="hidden" name="nome_produto" value="<?php echo $linha["nomeProduto"]; ?>">
                    <input type="submit" class="botao" value="Comprar Agora" name="comprar<?php echo $i; ?>">
                    <?php
                        if(isset($_POST["comprar". $i])){

                            header("Location: tela/login.php");
                        }
                    ?>
                </form>
                <?php
                $i++;
                ?>
            </div>
<?php
        }
    }
}

?>

        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">FlyingLocation</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="">Inicio</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="tela/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="tela/cadastro.php">Cadastrar-se</a>
                    </li>
                    <li class="nav-item">
                    <a href="tela/sobre_nos.php" class="nav-link">Sobre Nós</a>
                    </li>
                </ul>
                </div>
            </div>
        </nav>

        <h1 class="homepage">Bem-vindo!</h1> 
        <h5 class = "homepage">Estamos felizes que tenha nos visitado. Para comprar qualquer produto, <a href = "tela/login.php">faça login</a>, primeiro.</h5>
        <!-- <p class = "homepage">Para ter acesso às compras da loja, faça <a href = "tela/login.php">login</a>. </p> -->

        <form action="loja.php" method="GET">
            <input type="text" name="termo_pesquisa" placeholder="Pesquise sobre os produtos" value="<?php echo isset($_GET['termo_pesquisa']) ? htmlspecialchars($_GET['termo_pesquisa']) : ''; ?>">
            <button type="submit">Pesquisar</button>
        </form>
        
        <?php if(isset($_GET['termo_pesquisa']) && !empty($_GET['termo_pesquisa'])): ?>
            <?php

                $termo_pesquisa = $_GET['termo_pesquisa'];
                
                $res_inicio = $banco->buscaSQL("*","Produtos", "WHERE", "nomeProduto LIKE '$termo_pesquisa%'");

                $res = $banco->buscaSQL("*", "Produtos", "WHERE", "nomeProduto LIKE '%$termo_pesquisa%' AND nomeProduto NOT LIKE '$termo_pesquisa%'");

            ?>
        <?php else:$res_inicio = null;
            $res = null;
        ?>

        <?php endif; ?>

        <?php

            if($_SESSION["aviso"] == "yes"){

                $aviso = $_SESSION["aviso"];
                echo "<h2 style = 'background-color: red'>Uma mensagem foi enviada ao e-mail que foi inserido.<br>Verifique se ele está lá.</h2>";

            }

        ?>

        <?php if($res_inicio && mysqli_num_rows($res_inicio) > 0):?>
            <div class="container">
                <div class="produtos row">
            <?php 

                exibirProdutos($res_inicio);
                if($res && mysqli_num_rows($res) > 0){
                    exibirProdutos($res);
                }
             ?>
                </div>
            </div>
        <?php elseif($res && mysqli_num_rows($res) > 0): ?>
            <div class="container">
                <div class="produtos row">
                <?php 
                    exibirProdutos($res);
                ?>
                </div>
            </div>
        <?php else:?>
            <div class="container">
                <div class="produtos row">
            <?php exibirProdutos($cons2); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- <h5 class="homepage">Autores: João Victor Brum de Castro e José Claion --sobrenome--</h5>
        <h5 class="homepage">E-mails:<br>
        joaovictor.brumc@gmail.com<br>
        claionviado@gaymail.com<br>
        </h5> -->
    
    <script src="assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
    <script src="assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    </body>
</html>
<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require("Adaptador/BDAcesso.php");// Dessa forma está correta.

    use padroes_projeto\Adaptador\BDAcesso;

    $banco = BDAcesso::getInstance();

    $cons2 = $banco->buscaSQL("*", "produtos");

    $pegar_data1 = $banco->buscaSQL("*", "usuarios_temporarios");

    if(mysqli_num_rows($pegar_data1) > 0){  

        while($lin_temp = mysqli_fetch_assoc($pegar_data1)){

            $data_limite = $lin_temp["data_expiracao"];

            $data_atual = new DateTime();
        
            $data_expiracao = new DateTime($data_limite);

            if($data_atual > $data_expiracao){

                $data_expiracao_formatada = $data_expiracao->format('Y-m-d H:i:s');

                $apagar_temp = $banco->excluirDados("usuarios_temporarios", "WHERE", "'$data_expiracao_formatada' < NOW()");
            }
        }
    }

    $pegar_data2 = $banco->buscaSQL("*", "tokens");


    if(mysqli_num_rows($pegar_data2) > 0){  

        while($lin_temp = mysqli_fetch_assoc($pegar_data2)){

            $data_limite = $lin_temp["data_expiracao"];

            $data_atual = new DateTime();
        
            $data_expiracao = new DateTime($data_limite);

            if($data_atual > $data_expiracao){

                $data_expiracao_formatada = $data_expiracao->format('Y-m-d H:i:s');

                $apagar_temp = $banco->excluirDados("tokens", "WHERE", "'$data_expiracao_formatada' < NOW()");
            }
        }
    }



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página inicial</title>

    <link rel="stylesheet" href="assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/basic.css">
    <link rel="icon" type = "image/jpeg" href="assets/img/personalizacao/logo_diminuida.jpeg">

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
            $preco = str_replace(".",",", $linha["preco"]);

?>
            <div class="produto col-6 col-md-3 m-3" id="width-20rem">
                <img class="img-thumbnail" src="assets/img/products/<?php echo $linha["imagem_produto"]; ?>" alt="Produto 1">
                <h3><?php echo $linha["nome_produto"]; ?></h3>
                <p class="preco"><?php echo "R$ " . $preco; ?></p>

                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <input type="hidden" name="nome_produto" value="<?php echo $linha["nome_produto"]; ?>">
                    <a class="botao" href = "tela/login.php" style = "color: white">Comprar Agora</a>
                </form>
                <?php
                $i++;

                // if(isset($_POST["comprar_$i"])){

                //     header("Location: tela/login.php");
                // }
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
                
                $res_inicio = $banco->buscaSQL("*","produtos", "WHERE", "nome_produto LIKE '$termo_pesquisa%'");

                $res = $banco->buscaSQL("*", "produtos", "WHERE", "nome_produto LIKE '%$termo_pesquisa%' AND nome_produto NOT LIKE '$termo_pesquisa%'");

            ?>
        <?php else:$res_inicio = null;
            $res = null;
        ?>

        <?php endif; ?>

        <?php

            if(isset($_SESSION["aviso"])){

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

    <script src="assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
    <script src="assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    </body>
</html>
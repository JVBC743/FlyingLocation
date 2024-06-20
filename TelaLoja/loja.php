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
    $res = null;
    
    if(isset($_SESSION["email"])){
        $email = $_SESSION["email"];
    }
    else{
        $email = null;
    }
    
    $banco = BDAcesso::getInstance();
    
    $cons = $banco->buscaSQL("*", "usuarios", "WHERE", "email = '$email'");
    
    if(mysqli_num_rows($cons) > 0){
        $user_log = mysqli_fetch_assoc($cons);
        $nome_usuario = $user_log["nomePessoa"];
        $caminho = $user_log["caminho"];
        $imagem_pss = $user_log["imagem_pessoa"];
        $cargo = $user_log["cargo"];
    }

    $_SESSION["nome_usuario"] = $nome_usuario;
    
    $cons1 = $banco->buscaSQL("cepPessoa","usuarios","WHERE","nomePessoa = '$nome_usuario'");

    $cons2 = $banco->buscaSQL("*", "produtos");

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

<?php
function exibirprodutos($consultaSQL)
{
    if ($consultaSQL) {
        $i = 0;
        while (($linha = mysqli_fetch_assoc($consultaSQL))) {
            $preco = str_replace(".",",", $linha["preco"]);

?>
            <div class="produto col-6 col-md-3 m-3" id="width-20rem">
                <img class="img-thumbnail" src="../assets/img/products/<?php echo $linha["imagem_produto"] ?>" alt="Produto 1">
                <h3><a href="produto.php"><?php echo $linha["nome_produto"]; ?></a></h3>
                <p class="preco"><?php echo "R$ " . $preco; ?></p>

                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <input type="hidden" name="nome_produto" value="<?php echo $linha["nome_produto"]; ?>">
                    <input type="submit" class="botao" value="Comprar Agora" name="comprar<?php echo $i; ?>">
                </form>                                                         
                <?php
                $i++;
                ?>
            </div>
<?php
        }
    }
}

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        foreach ($_POST as $key => $value) {
            if (strpos($key, "comprar") === 0) {
                $i = substr($key, 7); 

                $nome_produto = $_POST["nome_produto"];
                $_SESSION["nome_produto"] = $nome_produto;
                $_SESSION["nome_usuario"] = $nome_usuario;

                header("Location: produto.php");
                exit; 
            }
        }
    }
?>

    <?php if($nome_usuario == null): ?> <!--//OLHA O IFFFF-->

        <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
        <a href = "../index.php">Voltar</a>

    <?php else: ?>
<!-- ============================================================NAV BAR=================================================================================== -->

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
                        <a class="nav-link" aria-current="page" href = "editar_prod.php">Meus produtos</a>
                    </li>

                    <?php if($cargo == 'administrador'): ?>
                    <li class="nav-item">

                    <form action="loja.php" method = "post">

                        <input type = "submit" href = "../tela/lista_usuarios.php" class="nav-link" aria-current="page" value = "Lista de Usuários" name = "lista">

                    </form>

                    <?php

                        if(isset($_POST["lista"])){

                            header("Location: ../tela/lista_usuarios.php");

                        }
                    ?>
                        

                    </li>

                    <?php endif; ?>

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

                                    $_SESSION["nome_usuario"] = $nome_usuario;
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

<!-- ============================================================NAV BAR=================================================================================== -->


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

        <?php if($res_inicio && mysqli_num_rows($res_inicio) > 0):?>
            <div class="container">
                <div class="produtos row">
            <?php 

                exibirprodutos($res_inicio);
                if($res && mysqli_num_rows($res) > 0){
                    exibirprodutos($res);
                }
             ?>
                </div>
            </div>
        <?php elseif($res && mysqli_num_rows($res) > 0): ?>
            <div class="container">
                <div class="produtos row">
                <?php 
                    exibirprodutos($res);
                ?>
                </div>
            </div>
        <?php else:?>
            <div class="container">
                <div class="produtos row">
            <?php exibirprodutos($cons2); ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>

</body>
</html>





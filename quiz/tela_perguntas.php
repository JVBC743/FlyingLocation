<?php

    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require("../Adaptador/BDAcesso.php"); // Dessa forma está correta.
    use padroes_projeto\Adaptador\BDAcesso;

    $banco = BDAcesso::getInstance();

    $numero = 1;

    $nome_usuario = $_SESSION["nome_usuario"];

    $cons = $banco->buscaSQL("*", "usuarios", "WHERE", "nome = '$nome_usuario'");

    if(mysqli_num_rows($cons) > 0){

        $linha = mysqli_fetch_assoc($cons);

        $cargo = $linha["cargo"];

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>

    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/basic.css">
    <link rel="icon" type = "image/jpeg" href="../assets/img/personalizacao/logo_diminuida.jpeg">

</head>
<body>
    
<!-- ============================================================NAV BAR=================================================================================== -->

<nav class="navbar navbar-expand-lg bg-body-tertiary">  
            <div class="container-fluid">
                <a class="navbar-brand" href="../TelaLoja/loja.php">FlyingLocation</a>                 
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                   <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../TelaLoja/loja.php">Loja</a>
                    </li>

                    <?php if($cargo == 'administrador' || $cargo == 'fornecedor'):?>
                        <li class="nav-item">

                        <form action = "<?php echo $_SERVER["PHP_SELF"];?>" method = "post">
                            <input type = "submit" class="nav-link" value = "Cadastrar Produto" name = "cad_prod">
                        </form>

                        <?php 
                                if(isset($_POST["cad_prod"])){
                                    
                                    $_SESSION["nome_usuario"];                                 
                                    header("Location: ../TelaLoja/gerenciar_prod.php");
                                }
                        ?>
                        </li>     
                    <?php endif;?>  

                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href = "../TelaLoja/editar_prod.php">Meus Produtos</a>
                    </li>

                    <li class="nav-item">
                        <input type = "submit" name = "quiz" class="nav-link" aria-current="page" href = "../quiz/tela_perguntas.php" value = "Quiz">
                    </li>
                    <?php 

                        if(isset($_POST["quiz"])){

                            header("Location: ../quiz/tela_perguntas.php");
                        }
                    ?>


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
                    
                    <?php if (!empty($imagem_pss)):?>
                        <div class="d-flex" > 
                        <div class="dropdown">
                          <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                              <img class="me-2" src="../assets/img/users/<?php echo $imagem_pss; ?>" style = "height: 50px; width: 50px;">

                          </button>
                          <form action = "<?php echo $_SERVER["PHP_SELF"]; ?>" method = "post">
                          <ul class="dropdown-menu">
                            <li class = "nav-link active"><input type = "submit" class="dropdown-item" value = "Perfil" name = "perfil"></li>
                            <li><input type = "submit" class="dropdown-item" value = "Sair" name = "sair"></li>
                          </ul>
                          </form>
                            <?php 
                                if(isset($_POST["perfil"])){

                                    if(isset($_SESSION["editar_usuario"])){

                                        unset($_SESSION["editar_usuario"]);

                                    }

                                    $_SESSION["nome_usuario"];
                                    header("Location: ../tela/perfil.php");
                                }
                                if(isset($_POST["sair"])){
                                    session_destroy();
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

    <?php
    
        $numero_perg = rand(1, 100);

        //Ideia: criar um session para passar o valor do id da pergunta, recarregar a página, e verificar se o número é igual.
    
    ?>

    <div class="mb-3">
        <h4>Pergunta X</h4>
    </div>
    <div class="form-check">
    <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
    <label class="form-check-label" for="exampleRadios1">
        Default radio
    </label>
    </div>
    <div class="form-check">
    <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
    <label class="form-check-label" for="exampleRadios2">
        Second default radio
    </label>
    </div>
    <div class="form-check">
    <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="option3" disabled>
    <label class="form-check-label" for="exampleRadios3">
        Disabled radio
    </label>
    </div>

    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>

    
</body>
</html>
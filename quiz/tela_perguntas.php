<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require("../Adaptador/BDAcesso.php"); 
    use padroes_projeto\Adaptador\BDAcesso;

    $banco = BDAcesso::getInstance();

    $numero = 1;

    if(isset($_SESSION["nome_usuario"])){

        $nome_usuario = $_SESSION["nome_usuario"];

    }else{

        $nome_usuario = null;
    }

    $cons = $banco->buscaSQL("*", "usuarios", "WHERE", "nome = '$nome_usuario'");

    if(mysqli_num_rows($cons) > 0){

        $linha1 = mysqli_fetch_assoc($cons);
        $cargo = $linha1["cargo"];
        $imagem_pss = $linha1["imagem_pessoa"];
        $credito = $linha1["credito"];

    }

    $cons_pergunta = $banco->buscaSQL("*", "perguntas");

    if($cons_pergunta){

        $num_linhas = mysqli_num_rows($cons_pergunta);
    }

    $numero_perg = rand(1, $num_linhas);

    $cons_pergunta2 = $banco->buscaSQL("*", "perguntas", "WHERE", "id_pergunta = $numero_perg");

    if(mysqli_num_rows($cons_pergunta2) == 0) {
        header("Refresh: 0");
    }
    //Procurar o enunciado pelo ID

    if($cons_pergunta2 && mysqli_num_rows($cons_pergunta2) > 0){

        $linha4 = mysqli_fetch_assoc($cons_pergunta2);
        $enunciado = $linha4["enunciado"];
        $pontuacao = $linha4["pontuacao"];
    }

   

    $cons_id_alternativa = $banco->buscaSQL("id_alternativa", "perguntas_alternativas", "WHERE", "id_pergunta = $numero_perg");

    //Procurar os IDs das alternativas.

    if(isset($_POST["cadastro_perguntas"])){

        header("Location: cadastro_perguntas.php");

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
    <link rel="icon" type="image/jpeg" href="../assets/img/personalizacao/logo_diminuida.jpeg">

</head>
<body>

    <?php if($nome_usuario == null): ?>

        <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
        <a href = "../index.php">Voltar</a>

    <?php else: ?>

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
                            <a class="nav-link" aria-current="page" href="../TelaLoja/loja.php">Loja</a>
                        </li>

                        <?php if($cargo == 'administrador' || $cargo == 'fornecedor'):?>
                            <li class="nav-item">

                            <form action = "<?php echo $_SERVER["PHP_SELF"];?>" method = "post">
                                <input type = "submit" class="nav-link" value = "Cadastrar Produto" name = "cad_prod">
                            </form>

                            <?php 
                                    if(isset($_POST["cad_prod"])){
                                        
                                        $_SESSION["nome_usuario"];                                 
                                        header("Location: ../TelaLoja/cad_geren_prod.php");
                                    }
                            ?>
                            </li>     
                        <?php endif;?>  

                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href = "../TelaLoja/lista_produtos.php">Meus Produtos</a>
                        </li>

                        <li class="nav-item">
                            <form action = "<?php echo $_SERVER["PHP_SELF"]; ?>" method = "post">
                                <input type = "submit" name = "quiz" class="nav-link active" aria-current="page" value = "Quiz">
                            </form>
                        </li>
                        <?php 

                            if(isset($_POST["quiz"])){

                                header("Location: ../quiz/tela_perguntas.php");

                            }
                        ?>


                        <?php if($cargo == 'administrador'): ?>
                        <li class="nav-item">

                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method = "post">

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

                            <li style = "list-style-type: none; margin-left: auto; position: relative; left: 250px;"><?php echo "R$" . $credito; ?></li>

                            <div class="d-flex" style = "list-style-type: none; margin-left: auto"> 
                            <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                <img class="me-2" src="../assets/img/users/<?php echo $imagem_pss; ?>" style = "height: 50px; width: 50px;">

                            </button>
                            <form action = "<?php echo $_SERVER["PHP_SELF"]; ?>" method = "post">
                            <ul class="dropdown-menu">
                                <li class = "nav-link active"><input type = "submit" class="dropdown-item" value = "Perfil" name = "perfil"></li>
                                <li><input type = "submit" class="dropdown-item" value = "Sair" name = "sair"></li>
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
                        </ul>

                        <?php endif; ?>

                </div>

                <!-- <li style = "list-style-type: none; margin-left: auto"><?php echo $credito; ?></li> -->

            </div>
        </nav>

<!-- ============================================================NAV BAR=================================================================================== -->

    <div class="mb-3 mt-5  d-flex flex-column align-items-center">
        <h4><?php 
        
        if(empty($enunciado)){

            echo "Pergunta não encontrada.";

        }else{

            echo $enunciado;

        } ?></h4>
        <div class="d-flex flex-column align-items-start">

    <?php


    //Ideia: criar um session para passar o valor do id da pergunta, recarregar a página, e verificar se o número é igual.

        if($cons_id_alternativa && mysqli_num_rows($cons_id_alternativa) > 0){

            while($linha5 = mysqli_fetch_assoc($cons_id_alternativa)){

                $id_alternativa = $linha5["id_alternativa"];

                $cons_alternativa = $banco->buscaSQL("*", "alternativas", "WHERE", "id_alternativa = $id_alternativa");
                //Procurando as descrições das alternativas

                if($cons_alternativa && mysqli_num_rows($cons_alternativa) > 0){

                    $linha6 = mysqli_fetch_assoc($cons_alternativa);

                    $descricao = $linha6['descricao'];
    ?>
                    <div class="form-check">

                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method = "post">

                                <input class="form-check-input" type="radio" name="alternativa" value="<?php echo $descricao; ?>" >
                                <label class="form-check-label" for="alternativa_<?php echo $descricao; ?>">
                            
                            


                            <?php echo $descricao; ?>

                        </label>
                    </div>
    <?php

                }
            }
        }
    ?>
                            <br>
                            </div>
                            <input type="submit" name = "enviar_pergunta" class="btn btn-success">

                        </form>
                        
    
    <?php
 
        if(isset($_POST["enviar_pergunta"])){

            if(isset($_POST["alternativa"])){

                $alt_escolhida = $_POST["alternativa"];

                $cons_resposta = $banco->buscaSQL("*", "alternativas", "WHERE", "descricao = '$alt_escolhida'");

                if($cons_resposta && mysqli_num_rows($cons_resposta) > 0){
                    $linha7 = mysqli_fetch_assoc($cons_resposta);

                    $verdadeiro = $linha7["verdadeiro"];
                
                }

                if($verdadeiro == 1){

                    echo "<br>Parabens, você acertou, ganhaste $pontuacao pontos";

                    $soma_credito = $credito + $pontuacao;



                    $banco->atualizarDados("usuarios","credito = $soma_credito", "WHERE", "nome = '$nome_usuario'");

                }else{

                    echo "<br>Errrrouuuu!";

                }
            }else{

                echo "<br>Escolha uma das perguntas.";
            }
        }
    ?>

        <br><br>

        <?php if($cargo == "administrador"):  ?>

            <form action="<?php $_SERVER["PHP_SELF"] ?>" method = "POST">

                <input type = "submit" name = "cadastro_perguntas" class="btn btn-success" style = "background-color: blue" value = "Abrir Tela de Cadastro de Perguntas">

            </form>



        <?php endif; ?>
        </div>

    <?php endif; ?>
        
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
</body>
</html>
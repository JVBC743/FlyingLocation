<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require("../Adaptador/BDAcesso.php"); 
    use padroes_projeto\Adaptador\BDAcesso;

    $banco = BDAcesso::getInstance();

    if(isset($_SESSION["nome_usuario"])){

        $nome_usuario = $_SESSION["nome_usuario"];

    }else{

        $nome_usuario = null;
    }

    $cons = $banco->buscaSQL("*", "usuarios", "WHERE", "nome = '$nome_usuario'");

    if(mysqli_num_rows($cons) > 0){

        $linha = mysqli_fetch_assoc($cons);
        $cargo = $linha["cargo"];
        $imagem_pss = $linha["imagem_pessoa"];
        $credito = $linha["credito"];

    }
    
?>

    <?php if($nome_usuario == null): ?>

    <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
    <a href = "../index.php">Voltar</a>

    <?php else: ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Perguntas</title>

    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/basic.css">
    <link rel="icon" type="image/jpeg" href="../assets/img/personalizacao/logo_diminuida.jpeg">

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


            

    <div class="d-flex flex-column align-items-center">

        <h1>Você abriu a tela de cadastro de perguntas.</h1>
        <form action="cadastro_perguntas.php" method = "POST">
            <div class="input-group mb-3">
                <span class="input-group-text" id="enunciado">Enunciado da pergunta</span>
                <input class="form-control" type="text" name = "enunciado" required> 
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="categoria">Categoria</span>
                <select class="form-select" name="categoria" aria-label="Default select example">
                    <option value="Matemática">Matemática</option>
                    <option value="História">História</option>
                    <option value="Ciência">Ciência</option>
                    <option value="Geografia">Geografia</option>
                    <option value="Cultura">Cultura</option>
                    <option value="Literatura">Literatura</option>
                </select>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="alternativa1">Alternativa 1</span>
                <input class="form-control" type="text" name="alternativa1"required> 
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="alternativa2">Alternativa 2</span>
                <input class="form-control" type="text" name="alternativa2"required> 
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="alternativa3">Alternativa 3</span>
                <input class="form-control" type="text" name="alternativa3"required> 
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="alternativa2">Alternativa 4</span>
                <input class="form-control" type="text" name="alternativa4"required> 
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="pontuacao">Pontuação</span>
                <input class="form-control" type="text" name = "pontuacao" required> 
            </div>
    
            <h3>Qual das alternativas é a correta?</h3>
            <div class="d-flex mt-4">

                <div class="form-check" style="margin-right: 1rem;">
                    <input type="radio" class="form-check-input" name="alts" value = "1" required>
                    <label class="form-check-label" for="flexRadioDefault1">
                        1
                    </label>
                </div>
                <div class="form-check" style="margin-right: 1rem;">
                    <input type="radio" class="form-check-input" name="alts" value = "2" required>
                    <label class="form-check-label" for="flexRadioDefault1">
                        2
                    </label>
                </div>
                <div class="form-check" style="margin-right: 1rem;">
                    <input type="radio" class="form-check-input" name="alts" value = "3" required>
                    <label class="form-check-label" for="flexRadioDefault1">
                        3
                    </label>
                </div>
                <div class="form-check mb-5" >
                    <input type="radio" class="form-check-input" name="alts" value = "4" required>
                    <label class="form-check-label" for="flexRadioDefault1">
                        4
                    </label>
                </div>
            </div>
            
            <input type="submit" class="btn btn-primary" name = "botao_cadastro_pergunta">
    
        </form>
    </div>

    <?php 
if(isset($_POST["botao_cadastro_pergunta"])){
    if(isset($_POST["categoria"]) && !empty($_POST["categoria"])){
        if(isset($_POST["alternativa1"]) && !empty($_POST["alternativa1"])){
            if(isset($_POST["alternativa2"]) && !empty($_POST["alternativa2"])){
                if(isset($_POST["alternativa3"]) && !empty($_POST["alternativa3"])){
                    if(isset($_POST["alternativa4"]) && !empty($_POST["alternativa4"])){

                        $enunciado = $_POST["enunciado"];
                        $categoria = $_POST["categoria"];
                        $alt1 = $_POST["alternativa1"];
                        $alt2 = $_POST["alternativa2"];
                        $alt3 = $_POST["alternativa3"];
                        $alt4 = $_POST["alternativa4"];
                        $pontuacao = $_POST["pontuacao"];

                        if(isset($_POST["alts"])){
                            
                            $caixa_marcada = $_POST["alts"];

                            $verdadeiro1 = ($caixa_marcada == 1) ? 1 : 0;
                            $verdadeiro2 = ($caixa_marcada == 2) ? 1 : 0;
                            $verdadeiro3 = ($caixa_marcada == 3) ? 1 : 0;
                            $verdadeiro4 = ($caixa_marcada == 4) ? 1 : 0;

                            $ins1 = $banco->inserirDados("perguntas", "'$categoria', $pontuacao, '$enunciado'", "categoria, pontuacao, enunciado" );

                            if($ins1){

                                $id_pergunta = mysqli_insert_id($banco->conexao);

                                $ins2 = $banco->inserirDados("alternativas", "'$alt1', $verdadeiro1", "descricao, verdadeiro");
                                $ins3 = $banco->inserirDados("alternativas", "'$alt2', $verdadeiro2", "descricao, verdadeiro");
                                $ins4 = $banco->inserirDados("alternativas", "'$alt3', $verdadeiro3", "descricao, verdadeiro");
                                $ins5 = $banco->inserirDados("alternativas", "'$alt4', $verdadeiro4", "descricao, verdadeiro");

                                if($ins2 && $ins3 && $ins4 && $ins5){


                                    $id_alt1 = mysqli_insert_id($banco->conexao);
                                    $ins6 = $banco->inserirDados("perguntas_alternativas", "'$id_pergunta', $id_alt1", "id_pergunta, id_alternativa");

                                    $id_alt2 = mysqli_insert_id($banco->conexao);
                                    $ins7 = $banco->inserirDados("perguntas_alternativas", "'$id_pergunta', '$id_alt2'", "id_pergunta, id_alternativa");

                                    $id_alt3 = mysqli_insert_id($banco->conexao);
                                    $ins8 = $banco->inserirDados("perguntas_alternativas", "'$id_pergunta', '$id_alt3'", "id_pergunta, id_alternativa");

                                    $id_alt4 = mysqli_insert_id($banco->conexao);
                                    $ins9 = $banco->inserirDados("perguntas_alternativas", "'$id_pergunta', '$id_alt4'", "id_pergunta, id_alternativa");

                                    if($ins6 && $ins7 && $ins8 && $ins9){

                                        echo "Pergunta e alternativas inseridas com sucesso!";

                                    } else {

                                        echo "Erro ao inserir as alternativas.";
                                    }
                                } else {

                                    echo "Erro ao inserir as alternativas.";
                                }
                            } else {

                                echo "Erro ao inserir a pergunta.";
                            }
                        }
                    }
                }
            }
        }
    }
}
?>

    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
</body>
</html>

<?php endif; ?>
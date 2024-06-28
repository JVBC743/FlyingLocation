<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("../Adaptador/BDAcesso.php");// Dessa forma está correta.
use padroes_projeto\Adaptador\BDAcesso;  

if(isset($_SESSION["nome_usuario"])){
    $nome_usuario = $_SESSION["nome_usuario"];
}
else{
    $nome_usuario = null;
}
$banco = BDAcesso::getInstance();

$cons = $banco->buscaSQL("*", "usuarios", "WHERE", "nome = '$nome_usuario'");

if(mysqli_num_rows($cons) > 0){
    $user_log = mysqli_fetch_assoc($cons);
    $imagem_pss = $user_log["imagem_pessoa"];
    $credito = $user_log["credito"];
    $cargo = $user_log["cargo"];

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/basic.css">
    <link rel="icon" type = "image/jpeg" href="../assets/img/personalizacao/logo_diminuida.jpeg">

</head>
<body>

    <?php if(!isset($_SESSION["nome_usuario"])): ?> <!--//OLHA O IFFFF-->

    <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
    <a href = "index.php">Voltar</a>
    
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
                                <input type = "submit" class="nav-link active" value = "Cadastrar Produto" name = "cad_prod">
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
                                <input type = "submit" name = "quiz" class="nav-link" aria-current="page" value = "Quiz">
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

    <div class="center">
        <h1>Tela de cadastramento de produtos</h1>
    </div>
    <div class="center">
        <h4>Insira abaixo os dados do produto que deseja cadastrar</h3>
    </div>
    <div class="center">
        <form action = "gerenciar_prod.php" method = "post" enctype="multipart/form-data"><!--Tem que colocar o UPDATE no campo da quantidade, para ser dinâmico-->
            
            <div class="input-group mb3" id="width-30rem">
                    <span class="input-group-text" id="basic-addon1">Nome</span>
                    <input type="text" class="form-control" name = "nome_produto" placeholder="Abacate" aria-label="Abacate" aria-describedby="basic-addon1">
            </div>
            <br>
            <div class="input-group mb3" id="width-30rem">
                    <span class="input-group-text" id="basic-addon1">Preço:</span>
                    <input type="text" class="form-control" name = "preco" placeholder="120,20" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <br>
            <div class="input-group mb3" id="width-30rem">
                    <span class="input-group-text" id="basic-addon1">Quantidade:</span>
                    <input type="text" class="form-control" name = "quantidade" placeholder="1, 2 ou infinito" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <br>
            <div class="input-group mb3" id="width-30rem">
                    <span class="input-group-text" id="basic-addon1">Fabricante</span>
                    <input type="text" class="form-control" name = "fabricante" placeholder="Gorginho da Silva" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <br>
            <div class="input-group mb3" id="width-30rem">
                    <span class="input-group-text" id="basic-addon1">Data de fabricação</span>
                    <input type="text" class="form-control" name = "data_fabrica" placeholder="01/03/1943" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <br>
            <div class="input-group mb3" id="width-30rem">
                    <span class="input-group-text" id="basic-addon1">Garantia:</span>
                    <input type="text" class="form-control" name = "garantia" placeholder="quantidade de em dias ex: 12"aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <br>
            <div class="input-group mb3" id="width-30rem">
                    <span class="input-group-text" id="basic-addon1">Descrição:</span>
                    <input type="text" class="form-control" name = "descricao" placeholder="Esse produto tem tantas qualidades" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <br>
            <div class="input-group mb3" id="width-30rem">
                    <span class="input-group-text" id="basic-addon1">Arquivo:</span>
                    <input type="file" class="form-control" name = "arquivoEnviado"  aria-label="Username" aria-describedby="basic-addon1"                                                                  laceholder="nig-">
            </div>
            <br>
            <div class="center-item">
                <input type = "submit" name = "enviar" value = "Enviar" class = "btn btn-primary">
            </div>
            </form>
        </div>

        <?php  

        $usuario = $_SESSION["nome_usuario"];

            if(isset($_POST["enviar"])){
                if(isset($_POST["nome_produto"]) && !empty($_POST["nome_produto"])){
                    if(isset($_POST["preco"]) && !empty($_POST["preco"])){
                        if(isset($_POST["quantidade"]) && !empty($_POST["quantidade"])){
                            if(isset($_POST["fabricante"]) && !empty($_POST["fabricante"])){
                                if(isset($_POST["data_fabrica"]) && !empty($_POST["data_fabrica"])){
                                    if(isset($_POST["garantia"]) && !empty($_POST["garantia"])){
                                        if(isset($_POST["descricao"]) && !empty($_POST["descricao"])){
                                            if (isset($_FILES["arquivoEnviado"]) && $_FILES["arquivoEnviado"]["size"] > 0){
                                                $nome = $_POST["nome_produto"];
                                                $preco = $_POST["preco"];
                                                $quant = $_POST["quantidade"];
                                                $fabri = $_POST["fabricante"];
                                                $data = $_POST["data_fabrica"];
                                                $garan = $_POST["garantia"];
                                                $descri = $_POST["descricao"];

                                                list($dia, $mes, $ano) = explode('/', $data);

                                                $data = date('Y-m-d', strtotime("$ano-$mes-$dia"));
                                                
                                                $targetDir = "../assets/img/products/"; 
                                                $fileName = basename($_FILES["arquivoEnviado"]["name"]);
                                                $targetFile = $targetDir . $fileName;
                                                $uploadOk = 1;
                                
                                                $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
                                                if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif" ) {
                                                    echo "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
                                                    $uploadOk = 0;
                                                }

                                                //É aqui que ele verifica se a imagem já está no sistema. A não ser que essa parte não seja isso.
                                                if (file_exists($targetFile)) {
                                                    echo "Desculpe, o arquivo já existe.";
                                                    $uploadOk = 0;
                                                }
                                
                                                if ($_FILES["arquivoEnviado"]["size"] > 500000) {
                                                    echo "Desculpe, o arquivo é muito grande.";
                                                    $uploadOk = 0;
                                                }
                                                if ($uploadOk == 1) {
                                                    if (move_uploaded_file($_FILES["arquivoEnviado"]["tmp_name"], $targetFile)) {
                                                        echo "O arquivo " . basename($_FILES["arquivoEnviado"]["name"]) . " foi enviado com sucesso.";
                                                    } else {
                                                        echo "Desculpe, ocorreu um erro ao enviar o arquivo.";
                                                    }
                                                }

                                                $preco = str_replace(",",".", $preco);

                                                $banco = BDAcesso::getInstance();

                                                //O "inserirDados()" é do tipo "void", então seria interessante apenas chamar esse método sozinho.
                                                //Sem atribuir à nenhuma variável. Ou, então, aplicar algum retorno à esse método.

                                                $inst = $banco->inserirDados("produtos", "'$nome', $preco, $quant, '$fabri', '$nome_usuario', '$data', $garan, '$descri', '$fileName'", "nome_produto, preco, quantidade, fornecedor, cadastrador, fabricacao, garantia, descricao, imagem_produto");
                                                    
                                                if($inst){
                                                    echo "Dados cadastrados com sucesso!<br>";
                                                }else{
                                                    echo "Houve algum erro no processo de cadastramento.";
                                                }
                                            }else{
                                                echo "Imagem não inserida!";
                                            }
                                        }else{

                                            echo "Descrição não inserida!";
                                        }   
                                    }else{
                                        echo "Dias de garantia não inserido!";
                                    }
                                }else{
                                    echo "Data de fabricação não inserida!";
                                }
                            }else{
                                echo "Nome do fabricante não inserido! Ou não registrado no nosso sistema!";
                            }
                        }else{
                            echo "Quantidade não inserida!";
                        }
                    }else{
                        echo "Preço não inserido!";
                    }
                }else{
                    echo "Nome não inserido!";
                }   
            }
        ?>    
    <?php endif; ?>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
</body>
</html>


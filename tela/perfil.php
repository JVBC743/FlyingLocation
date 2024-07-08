<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require("../Adaptador/BDAcesso.php"); // Dessa forma está correta.
    use padroes_projeto\Adaptador\BDAcesso;

    $banco = BDAcesso::getInstance();

    if (isset($_SESSION["editar_usuario"])) {
        $veredito = $_SESSION["editar_usuario"];
    } else {
        $veredito = $_SESSION["nome_usuario"];
    }

    $cons = $banco->buscaSQL("*", "usuarios", "WHERE", "nome = '$veredito'");
    
        if(mysqli_num_rows($cons) > 0){

            $linha = mysqli_fetch_assoc($cons);
            $senha = $linha["senha"];
            $imagem_pss = $linha["imagem_pessoa"];
            $cargo = $linha["cargo"];
            $cep = $linha["cep"];
            $numero = $linha["numero_casa"];
            $credito = $linha["credito"];

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
    <link rel="icon" type = "image/jpeg" href="../assets/img/personalizacao/logo_diminuida.jpeg">

</head>
<body>

    <?php if($_SESSION["nome_usuario"] == null): ?> 

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

                                        if(isset($_SESSION["editar_usuario"])){

                                            unset($_SESSION["editar_usuario"]);
    
                                        }
                                        
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

                                if(isset($_SESSION["editar_usuario"])){

                                    unset($_SESSION["editar_usuario"]);

                                }

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

                                if(isset($_SESSION["editar_usuario"])){

                                    unset($_SESSION["editar_usuario"]);

                                }

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

        <h1>Perfil de usuário</h1>

        <img src="../assets/img/users/<?php echo $imagem_pss; ?>" style = "margin: 20px; border-style: solid; height: 200px; width: 200px;">

        <form action = "perfil.php" method = "post" enctype="multipart/form-data">

            <div class="input-group mb3" id="width-30rem">
                    <span class="input-group-text" id="basic-addon1">Nome:</span>
                    <input type="text" class="form-control" name = "nome_usuario"  value = "<?php echo $veredito?>" aria-label="Username" aria-describedby="basic-addon1">
            </div>

            <br>

            <div class="input-group mb3" id="width-30rem">
                <span class="input-group-text" id="basic-addon1">Senha:</span>
                <input type="text" class="form-control" name = "senha_usuario"  value = "<?php echo $senha; ?>" aria-label="Username" aria-describedby="basic-addon1">
            </div>

            <br>

            <div class="input-group mb3" id="width-30rem">
                <span class="input-group-text" id="basic-addon1">CEP:</span>
                <input type="text" class="form-control" name = "cep_usuario"  value = "<?php echo $cep; ?>" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <br>

            <div class="input-group mb3" id="width-30rem">
                <span class="input-group-text" id="basic-addon1">N° da casa:</span>
                <input type="text" class="form-control" name = "numero_casa"  value = "<?php echo $numero; ?>" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <br>

            <div class="input-group mb3" id="width-30rem">
                <span class="input-group-text" id="basic-addon1">Imagem de perfil:</span>
                <input type = "file" name = "imagem_usuario" class="form-control" aria-label="Username" aria-describedby="basic-addon1"><br><br>
            </div>
            <br>
                            
            <input type = "submit" name = "alterar_dados" value = "Alterar Dados">

        </form>

        <form action = "<?php echo $_SERVER["PHP_SELF"]; ?>" method = "post">
            <button id="AbrirModal" type = "button" class="btn btn-danger">Excluir Conta</button>
            <dialog id="modal">
                    <h4>Tem a plena CERTEZA de que quer excluir a sua conta? (Essa ação não pode ser desfeita)</h4>
                    <input type = "submit" class="btn btn-danger" name = "excluir_ctz" value = "Excluir Mesmo!">
                    <button type="button" id="FechaModal" class="btn btn-success" >Cancelar</button>
            </dialog>
        </form>
                <?php 

                    if(isset($_POST["alterar_dados"])){
                        
                        if(isset($_POST["nome_usuario"]) || isset($_POST["senha"])){
                            
                            $novo_nome = $_POST["nome_usuario"];

                            $res1 = $banco->atualizarDados("usuarios", "nome = '$novo_nome'","WHERE", "nome = '$veredito'");

                            $novo_senha = $_POST["senha_usuario"];

                            $res2 = $banco->atualizarDados("usuarios", "senha = '$novo_senha'","WHERE", "nome = '$veredito'");

                            $novo_cep = $_POST["cep_usuario"];

                            $res3 = $banco->atualizarDados("usuarios", "cep = '$novo_cep'","WHERE", "nome = '$veredito'");
                            
                            $novo_numero = $_POST["numero_casa"];

                            $res4 = $banco->atualizarDados("usuarios", "numero_casa = '$novo_numero'","WHERE", "nome = '$veredito'");
                            
                            if($res1){
                                
                                $_SESSION["nome_usuario"] = $novo_nome;    
                                echo "Nome atualizada com sucesso!<br>";
                            }else{
                                echo "Falha na atualização do nome.<br>";
                            }

                            if($res2){
                                echo "Senha atualizada com sucesso!<br>";
                            }else{
                                echo "Falha na atualização da senha.<br>";
                            }

                            if($res3){
                                echo "CEP atualizado com sucesso!<br>";
                            }else{
                                echo "Falha na atualização do CEP.<br>";
                            }

                            if($res4){
                                echo "N° da casa atualizado com sucesso!<br>";
                            }else{
                                echo "Falha na atualização do N° da casa.<br>";
                            }
                        }    

                        if(isset($_FILES["imagem_usuario"]) && $_FILES["imagem_usuario"]["size"] > 0){

                            $targetDir = "../assets/img/users/"; 
                            $fileName = basename($_FILES["imagem_usuario"]["name"]); //Nome do arquivo
                            $targetFile = $targetDir . $fileName;
                            $uploadOk = 1;
    
                            $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
                            if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif" ) {
                                echo "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
                                $uploadOk = 0;
                            }
                            if (file_exists($targetFile)) {
                                echo "Desculpe, o arquivo já existe.";
                                $uploadOk = 0;
                            }
    
                            if ($_FILES["imagem_usuario"]["size"] > 500000) {
                                echo "Desculpe, o arquivo é muito grande.";
                                $uploadOk = 0;
                            }
                            if ($uploadOk == 1) {
                                if (move_uploaded_file($_FILES["imagem_usuario"]["tmp_name"], $targetFile)) {
                                    echo "O arquivo " . basename($_FILES["imagem_usuario"]["name"]) . " foi enviado com sucesso.";
                                } else {
                                    echo "Desculpe, ocorreu um erro ao enviar o arquivo.";
                                }
                            }
    
                            $att_foto = $banco->atualizarDados("usuarios", "imagem_pessoa = '$fileName'", "WHERE", "nome = '$veredito'");
    
                            if($att_foto){
    
                                echo "Foto atualizda com sucesso!";
    
                            }else{
    
                                echo "Ocorreu um erro.";
    
                            }
                        }
                    }

                    if(isset($_POST["excluir_ctz"])){

                        $resp = $banco->excluirDados("usuarios", "WHERE", "nome = '$veredito'");

                        if($resp){

                            session_destroy();
                            header("Location: ../index.php");

                         }else{
                             echo "Conta não excluída.";
                         }
                     }
                ?>
        <?php endif; ?>

    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
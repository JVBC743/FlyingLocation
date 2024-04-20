<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require("../Adaptador/BDAcesso.php"); // Dessa forma está correta.
    use padroes_projeto\Adaptador\BDAcesso;

    $banco = BDAcesso::getInstance();

    if(isset($_SESSION["nome_usuario"])){

        $nome_usuario = $_SESSION["nome_usuario"];
        
    }else{

        $nome_usuario = null;
    }

    $cons = $banco->buscaSQL("*", "Pessoas", "WHERE", "nomePessoa = '$nome_usuario'");
    
    if(mysqli_num_rows($cons) > 0){

        $user_log = mysqli_fetch_assoc($cons);
        $caminho = $user_log["caminho"];
        $senha = $user_log["senhaPessoa"];
        $imagem_pss = $user_log["imagem_pessoa"];
        $cargo = $user_log["cargo"];
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
</head>
<body>

    <?php if($nome_usuario == null): ?> 

        <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
        <a href = "../index.php">Voltar</a>

    <?php else: ?>

        <!-- <img src = "../<?php echo $caminho ."/". $imagem_pss ?>" style = "height: 50px; width: 50px;"> -->

        <nav class="navbar navbar-expand-lg bg-body-tertiary">  
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">FlyingLocation</a>                 
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                   <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page">Loja</a>
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

                    </ul>
                    
                    <?php if (!empty($caminho) && !empty($imagem_pss)):?>
                        <div class="d-flex" > 
                        <div class="dropdown">
                          <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                              <img class="me-2" src="../<?php echo $caminho .'/'. $imagem_pss; ?>" style = "height: 50px; width: 50px;">
                          </button>
                          <form action = "<?php echo $_SERVER["PHP_SELF"]; ?>" method = "post">
                          <ul class="dropdown-menu">
                            <li class = "nav-link active"><input type = "submit" class="dropdown-item" value = "Perfil" name = "perfil"></li>
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

        <h1>Perfil de usuário</h1>


        <form action = "perfil.php" method = "post" enctype="multipart/form-data">

            <div class="input-group mb3" id="width-30rem">
                    <span class="input-group-text" id="basic-addon1">Nome:</span>
                    <input type="text" class="form-control" name = "nome_usuario"  value = "<?php echo $nome_usuario?>" aria-label="Username" aria-describedby="basic-addon1">
            </div>

            <div class="input-group mb3" id="width-30rem">

                <span class="input-group-text" id="basic-addon1">Senha:</span>
                <input type="text" class="form-control" name = "senha_usuario"  value = "<?php echo $senha; ?>" aria-label="Username" aria-describedby="basic-addon1">

            </div>

            <input type = "file" name = "imagem_usuario"><br>

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
                        
                        if(isset($_POST["nome_usuario"]) || isset($_POST["senhaPessoa"])){
                            
                            $novo_nome = $_POST["nome_usuario"];

                            $res1 = $banco->atualizarDados("Pessoas", "nomePessoa = '$novo_nome'","WHERE", "nomePessoa = '$nome_usuario'");

                            $novo_senha = $_POST["senha_usuario"];

                            $res2 = $banco->atualizarDados("Pessoas", "senhaPessoa = '$novo_senha'","WHERE", "nomePessoa = '$nome_usuario'");

                            if($res1){
                                
                                $_SESSION["nome_usuario"] = $novo_nome;    
                                echo "Nome atualizada com sucesso!";

                            }else{
                                echo "Falha na atualização do nome.";
                            }
                            if($res2){
                                echo "Senha atualizada com sucesso!";
                            }else{
                                echo "Falha na atualização da senha.";
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
    
                            $att_foto = $banco->atualizarDados("Pessoas", "imagem_pessoa = '$fileName'", "WHERE", "nomePessoa = '$nome_usuario'");
    
                            if($att_foto){
    
                                echo "Foto atualizda com sucesso!";
                                header("Refresh: 2");
    
                            }else{
    
                                echo "Ocorreu um erro.";
    
                            }
                        }

                        header("Refresh: 1");
                    }

                    if(isset($_POST["excluir_ctz"])){

                        $resp = $banco->excluirDados("Pessoas", "WHERE", "nomePessoa = '$nome_usuario'");

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
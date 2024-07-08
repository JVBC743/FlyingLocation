<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once "../Adaptador/BDAcesso.php";
    use padroes_projeto\Adaptador\BDAcesso;
    $banco = BDAcesso::getInstance();

    if($_SESSION["nome_usuario"]){

        $nome_usuario = $_SESSION["nome_usuario"];
        
    }else{

        echo "Error no usuário";
    }

    $cons = $banco->buscaSQL("*", "usuarios", "WHERE", "nome = '$nome_usuario'");
    
    if(mysqli_num_rows($cons) > 0){

        $user_log = mysqli_fetch_assoc($cons);
        $imagem_pss = $user_log["imagem_pessoa"];
        $credito = $user_log["credito"];

    }
    

    $busca_cargo = $banco->buscaSQL("cargo", "usuarios", "WHERE", "nome = '$nome_usuario'");
    
    $consulta = $banco->buscaSQL("*","produtos", "WHERE" , "fornecedor = '$nome_usuario'");

    if($consulta && mysqli_num_rows($consulta) > 0){

        $linha = mysqli_fetch_assoc($consulta);

        // $usr_prod = $linha["fornecedor"];

        // echo "Fornecedor encontrado";

    }else{

        // echo "Fornecedor não encontrado.";

    }

    if($busca_cargo && mysqli_num_rows($busca_cargo) > 0 ){

        $linha = mysqli_fetch_assoc($busca_cargo);

        $cargo = $linha["cargo"];

    }else{

        echo "Cargo não encontrado.";

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/basic.css">
    <link rel="icon" type = "image/jpeg" href="../assets/img/personalizacao/logo_diminuida.jpeg">

</head>
<body>

    <?php if($nome_usuario == null): ?>

    <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
    <a href="../index.php">Voltar</a>

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
                            <a class="nav-link active" aria-current="page" href = "../TelaLoja/lista_produtos.php">Meus Produtos</a>
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


        <?php if($cargo == "cliente"): ?>

            <h1>Quer cadastrar um produto? Torne-se um fornecedor!</h1><br>

            <form action = "<?php echo $_SERVER["PHP_SELF"]; ?>" method = "post">
                <input type = "submit" name  = "tornar_fornecedor" value = "Clique aqui!">
            </form>

            <?php 
            
                if(isset($_POST["tornar_fornecedor"])){

                    $alt_cargo = $banco->atualizarDados("usuarios", "cargo = 'fornecedor'", "WHERE", "nome = '$nome_usuario'");
                    sleep(3);
                    header("Location: produto_edit.php");
                }
            ?>
            
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Quantidade</th>
                        <th scope="col">Data Fabricação</th>
                        <th scope="col">Garantia</th>
                        <th scope="col">Fornecedor</th>
                        <th scope="col">Descrição</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                <?php 

                    if($cargo == "fornecedor"){

                        $consulta = $banco->buscaSQL("*","produtos", "WHERE", "fornecedor = '$nome_usuario'");
                        
                    }elseif($cargo == "administrador"){

                        $consulta = $banco->buscaSQL("*","produtos");

                    }     
                        $i = 0;

                        while ($linha = mysqli_fetch_assoc($consulta)){

                            $id = $linha["id_produto"];
                            $nome = $linha["nome_produto"];
                            $preco = $linha["preco"];
                            $quant = $linha["quantidade"];
                            $data = $linha["fabricacao"];
                            $garantia = $linha["garantia"];
                            $desc = $linha["descricao"];
                            $img = $linha["imagem_produto"];
                            $fornecedor = $linha["fornecedor"];
                ?>
                            <tr>
                                <th scope="row"><?php echo $id; ?></th>
                                <td><?php echo $nome; ?></td>
                                <td><?php echo $preco; ?></td>
                                <td><?php echo $quant; ?></td>
                                <td><?php echo $data; ?></td>
                                <td><?php echo $garantia; ?></td>
                                <td><?php echo $fornecedor; ?></td>
                                <td><?php echo $desc; ?></td>
                                
                                <td>
                                    <div class="btn-group" role="group">
                                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="d-inline">

                                            <input type="hidden" name="nome_produto_<?php echo $i; ?>" value="<?php echo $nome; ?>">
                                            <button type="submit" class="btn btn-primary" name="editar">Editar</button>
                                            
                                        </form>
                                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="d-inline">
                                            <input type="hidden" name="apagar_produto_<?php echo $i; ?>" value="<?php echo $nome; ?>">
                                            <button type="button"id="delAbrirModal<?php echo $i;?>" class="btn btn-danger">Apagar</button>
                                        </form>

                                        <button type="button" id="abrirModal<?php echo $i;?>" >Ver Imagem</button>
                                    </div>
                                </td>
                            </tr>
                            <dialog id="modal<?php echo $i;?>">
                                <img src="../users/img/products/<?php echo $img ?>" alt="img">
                                <button type="button" id="fecharModal<?php echo $i;?>" >Fechar</button>
                            </dialog>

                            <dialog id="delModal<?php echo $i;?>">
                                <form action = "<?php echo $_SERVER["PHP_SELF"]; ?>" method = "post">
                                    <?php echo $i;?>
                                    <h4>Tem a plena CERTEZA de que quer excluir este produto? (Essa ação não pode ser desfeita)</h4>
                                    <input type = "submit" class="btn btn-danger" name = "excluir_ctz_<?php echo $i; ?>" value = "Excluir Mesmo!">
                                    
                                <form>
                                    <button type="button" id="delFecharModal<?php echo $i;?>" class="btn btn-success" >Cancelar</button>

                                <?php
                                    if(isset($_POST["excluir_ctz_$i"])){
                                                                            
                                            $resultado = $banco->excluirDados("produtos", "WHERE", "nome_produto = '$nome'");

                                            if($resultado){

                                                header("Refresh: 1");

                                            }else{

                                                echo "<h1>Ocorreu um erro ao apagar o produto.<h1>";
                                            }
                                        }
                                ?>
                            </dialog>
                <?php
                        $i++;
                    } 
                ?>
                </tbody>
            </table>

        
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                foreach ($_POST as $key => $value) {
                    if (strpos($key, "nome_produto_") === 0) {
                        $i = substr($key, 12);
                        $nome_produto = $_POST[$key];
                        $_SESSION["nome_produto"] = $nome_produto;
                        echo '<script>window.location="produto_edit.php";</script>'; 
                        exit();
                    }
                }
            }

        ?>
        
        <?php endif; ?>
    <?php endif; ?>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
    <script src="../assets/js/modals.js"></script>
</body>
</html>
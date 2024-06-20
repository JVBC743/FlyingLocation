<?php

    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require("../Adaptador/BDAcesso.php");

    use padroes_projeto\Adaptador\BDAcesso;

    $banco = BDAcesso::getInstance();

    $nome_usuario = $_SESSION["nome_usuario"];


    if($_SESSION["nome_usuario"] != null){

        $_SESSION["nome_usuario"] = $nome_usuario;

    }

    $cons1 = $banco->buscaSQL("cargo", "usuarios", "WHERE", "nome = '$nome_usuario'");

    if(mysqli_num_rows($cons1) > 0){

        $linha = mysqli_fetch_assoc($cons1);

        $cargo = $linha["cargo"];
        $id = $linha["id"];

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/basic.css">
</head>
<body>

    <?php if($nome_usuario == null && $cargo != "administrador"): ?>

        <h1>Você tentou acessar a página da lista de usuários sem estar logado SAI DAQUI</h1><br>
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
                            <a class="nav-link" aria-current="page" href = "../TelaLoja/loja.php">Loja</a>
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

                    <?php if($cargo == 'administrador'): ?>
                    <li class="nav-item">

                    <form action="loja.php" method = "post">

                        <input class="nav-link active" type = "submit" class="nav-link" aria-current="page" value = "Lista de Usuários" name = "lista">

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

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">CEP</th>
                        <th scope="col">N° da Residência</th>
                        <th scope="col">Nome da foto</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">E-mail</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody> 

                    <?php

                    $i = 0;

                        $consulta = $banco->buscaSQL("*","usuarios");
                    
                        while ($linha = mysqli_fetch_assoc($consulta)){

                            $id = $linha["id"];
                            $nome = $linha["nome"];
                            $cep = $linha["cep"];
                            $numero_casa = $linha["numero_casa"];
                            $imagem_pessoa = $linha["imagem_pessoa"];
                            $cargo_pss = $linha["cargo"];
                            $email = $linha["email"];
                            
                    ?>
                            <tr>
                                <th scope="row"><?php echo $id; ?></th>
                                <td><?php echo $nome; ?></td>
                                <td><?php echo $cep; ?></td>
                                <td><?php echo $numero_casa; ?></td>
                                <td><?php echo $imagem_pessoa; ?></td>
                                <td><?php echo $cargo_pss; ?></td>
                                <td><?php echo $email; ?></td>

                                <td>
                                    <div class="btn-group" role="group">
                                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="GET" class="d-inline">

                                            <input type="hidden" name="nome_pessoa_<?php echo $i; ?>" value="<?php echo $nome; ?>">
                                            <button type="submit" class="btn btn-primary" name="editar">Editar</button>
                                            
                                        </form>
                                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="d-inline">
                                            <input type="hidden" name="apagar_pessoa_<?php echo $i; ?>" value="<?php echo $nome; ?>">
                                            <button type="button"id="delAbrirModal<?php echo $i;?>" class="btn btn-danger">Apagar</button>
                                        </form>

                                        <?php

                                            if(isset($_POST["editar"])){

                                                $token = uniqid();

                                                $banco->inserirDados("Tokens", "'$token', '$id'", "'valorToken', 'idUsuario'");
                                                
                                                header("perfil.php");
                                            }
                                        ?>

                                        <button type="button" id="abrirModal<?php echo $i;?>" >Ver Imagem</button>
                                    </div>
                                </td>
                            </tr>
                            <dialog id="modal<?php echo $i;?>">
                                <img src="../assets/img/users/<?php echo $imagem_pessoa ?>" alt="img">
                                <button type="button" id="fecharModal<?php echo $i;?>" >Fechar</button>
                            </dialog>

                            <dialog id="delModal<?php echo $i;?>">
                                <form action = "lista_usuarios.php" method = "post">
                                    <?php echo $i;?>
                                    <h4>Tem a plena CERTEZA de que quer excluir este produto? (Essa ação não pode ser desfeita)</h4>
                                    <input type = "submit" class="btn btn-danger" name = "excluir_ctz_<?php echo $i; ?>" value = "Excluir Mesmo!">
                                    
                                <form>
                                    <button type="button" id="delFecharModal<?php echo $i;?>" class="btn btn-success" >Cancelar</button>

                                <?php
                                    if(isset($_POST["excluir_ctz_$i"])){
                                                                            
                                            $resultado = $banco->excluirDados("usuarios", "WHERE", "nome = '$nome'");

                                            if($resultado){

                                                header("Refresh: 1");

                                            }else{

                                                echo "<h1>Ocorreu um erro ao excluir o usuário.<h1>";
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

    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>

    <?php endif; ?>
                
    
</body>
</html>
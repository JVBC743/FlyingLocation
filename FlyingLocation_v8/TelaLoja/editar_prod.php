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

        echo "Batata";
    }

    $cons = $banco->buscaSQL("*", "Pessoas", "WHERE", "nomePessoa = '$nome_usuario'");
    
    if(mysqli_num_rows($cons) > 0){

        $user_log = mysqli_fetch_assoc($cons);
        $caminho = $user_log["caminho"];
        $imagem_pss = $user_log["imagem_pessoa"];

    }
    

    $busca_cargo = $banco->buscaSQL("cargo", "Pessoas", "WHERE", "nomePessoa = '$nome_usuario'");
    
    $consulta = $banco->buscaSQL("*","Produtos", "WHERE" , "fornecedor = '$nome_usuario'");

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
    <link rel="stylesheet" href="../assets/css/basic.css">
</head>
<body>

    <?php if($nome_usuario == null): ?>

    <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
    <a href="../index.php">Voltar</a>

    <?php else: ?>

        <nav class="navbar navbar-expand-lg bg-body-tertiary">  
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">FlyingLocation</a>                 
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                   <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="loja.php">Loja</a>
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
                        <a class="nav-link active" aria-current="page" href = "editar_prod.php">Meus Produtos</a>
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
                            <li><input type = "submit" class="dropdown-item" value = "Perfil" name = "perfil"></li>
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

                    $consulta = $banco->buscaSQL("*","Produtos", "WHERE", "fornecedor = '$nome_usuario'");
                    
                }elseif($cargo == "administrador"){

                    $consulta = $banco->buscaSQL("*","Produtos");

                }     
                    $i = 0;

                    while ($linha = mysqli_fetch_assoc($consulta)){

                        $id = $linha["idProduto"];
                        $nome = $linha["nomeProduto"];
                        $preco = $linha["precoProduto"];
                        $quant = $linha["quantidade"];
                        $data = $linha["dataFabricacao"];
                        $garantia = $linha["garantia"];
                        $desc = $linha["descricao"];
                        $cam = $linha["caminho"];
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
                                <button type="submit" class="btn btn-danger" >Apagar</button>
                            </form>
                            <button type="button" id="abrirModal<?php echo $i;?>" >Ver Imagem</button>
                        </div>
                    </td>
                </tr>
                <dialog id="modal<?php echo $i;?>">
                    <img src="../<?php echo $cam . $img ?>" alt="falhou">
                    <button type="button" id="fecharModal<?php echo $i;?>" >Fechar</button>
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
    <script src="../assets/bootstrap-5.3.3-dist/css/bootstrap.css"></script>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
    <script src="../assets/js/modals.js"></script>
</body>
</html>
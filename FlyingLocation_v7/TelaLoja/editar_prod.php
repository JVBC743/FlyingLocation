<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once "../Adaptador/BDAcesso.php";
    use padroes_projeto\Adaptador\BDAcesso;
    $banco = BDAcesso::getInstance();
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

    <?php if(!isset($_SESSION["nome_usuario"])): ?>

    <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
    <a href="../index.php">Voltar</a>

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
                $consulta = $banco->buscaSQL("*","Produtos"); 

                if($consulta && mysqli_num_rows($consulta)){
                    $i = 0;
                
                    while (($linha = mysqli_fetch_assoc($consulta))){
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
                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="d-inline">
                                <button id="AbrirModal" type="submit" class="btn btn-primary">Ver Imagem</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php
                    $i++;
                    }
                }
            ?>
            </tbody>
        </table>

        <dialog id="modal">
            
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Saepe vel iusto ad iure cumque fugit optio minus, dignissimos placeat quam quae minima at quisquam earum aperiam architecto ipsum esse temporibus!</p>

            <button id="FechaModal">Fecha</button>
        </dialog>


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
    <script src="../assets/js/main.js></script>
</body>
</html>
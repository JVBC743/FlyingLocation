<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once "../Adaptador/BDAcesso.php";// Dessa forma está correta.
    use padroes_projeto\Adaptador\BDAcesso;
    $banco = BDAcesso::getInstance();


    if(isset($_SESSION["nome_produto"])){
        $nome_produto = $_SESSION["nome_produto"];
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nome_produto; ?></title>
</head>
<body>
    <?php if($nome_produto == null): ?> <!--//OLHA O IFFFF-->

    <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
    <h1>Ou o produto que você acessou não existe.</h1><br>
    <a href = "../index.php">Voltar</a>

    <?php else: ?>

        <h1><?php echo $nome_produto; ?></h1>


    <?php endif; ?>
    
    
    
</body>
</html>

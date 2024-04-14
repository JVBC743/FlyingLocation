<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require("../Adaptador/BDAcesso.php");
require("../Adaptador/CepAdaptor.php");

use padroes_projeto\Adaptador\CepAdaptor;
use padroes_projeto\classes\Cliente;
use padroes_projeto\Adaptador\BDAcesso;

$cep_teste = new CepAdaptor;
$cliente = new Cliente;

$nome_usuario = $_SESSION["nome_usuario"];

$banco = BDAcesso::getInstance();
$resultado_cep = $banco->buscaSQL("cepPessoa", "Pessoas", "WHERE", "nomePessoa = '$nome_usuario'");

if ($resultado_cep && mysqli_num_rows($resultado_cep) > 0) {

    $linha = mysqli_fetch_assoc($resultado_cep);

    $cep_pessoa = $linha["cepPessoa"];
}


if (isset($_SESSION['nome_produto'])) {

    $nome_produto = $_SESSION['nome_produto'];

    $resul = $banco->buscaSQL("*", "Produtos", "WHERE", "nomeProduto = '$nome_produto'");

    if ($resul && mysqli_num_rows($resul) > 0) {
        $linha = mysqli_fetch_assoc($resul);
        $prod_nome = $linha["nomeProduto"];
        $prod_preco = $linha["precoProduto"];
    }
}

$res_casa = $banco->buscaSQL("numeracao", "Pessoas", "WHERE", "nomePessoa = '$nome_usuario'");

if ($res_casa && mysqli_num_rows($res_casa) > 0) {
    $linha_casa = mysqli_fetch_assoc($res_casa);
    $casa = $linha_casa["numeracao"];
}

if (isset($_POST["comprar"])) {
    echo "O produto será entregue no endereço: <br><br>";
    echo "N° da residência: " . $casa . "<br>";
    $cep_teste->lerCEP($cep_pessoa);
    $cep_teste->adaptarJson($cliente);
    $cep_teste->exibirDados();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $prod_nome ?></title>
</head>

<body>
    <h1><?php echo $prod_nome; ?></h1>
    <p><?php echo "Preço: " . $prod_preco; ?></p>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <input type="submit" name="comprar" value="Comprar">
    </form>
    <br>
</body>

</html>

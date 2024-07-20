<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require("../Adaptador/BDAcesso.php");
    require("../Adaptador/CepAdaptor.php");
    require '../PDF/vendor/autoload.php';

    use padroes_projeto\Adaptador\BDAcesso;
    use Dompdf\Dompdf;

        $dompdf = new Dompdf();

        if (!isset($_SESSION["nome_usuario"])) {

            echo "<script>window.alert('Você tentou acessar essa página sem estar logado.')</script>";
            header("Location: loja.php");
            
        }

        $nome_usuario = $_SESSION["nome_usuario"];

        $banco = BDAcesso::getInstance();
        $resultado_cep = $banco->buscaSQL("*", "usuarios", "WHERE", "nome = '$nome_usuario'");

        if ($resultado_cep && mysqli_num_rows($resultado_cep) > 0) {

            $linha = mysqli_fetch_assoc($resultado_cep);
            $cargo = $linha["cargo"];
            $credito = $linha["credito"];

        }

        $numero_pessoa = $_SESSION["numero_pessoa"];
        $rua_pessoa = $_SESSION["rua_pessoa"];
        $prod_nome = $_SESSION["prod_nome"];
        $prod_preco = $_SESSION["prod_preco"];
        $estado = $_SESSION["estado"];
        $cidade = $_SESSION["cidade"];

        $html = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>PDF Example</title>
        </head>
        <body>
            <h1 style = 'color: red; '>A sua compra foi um sucesso!</h1>
            <h5>O produto $prod_nome será entregue no endereço:</h5>

            <h4>Estado: $estado</h4>
            <h4>Cidade: $cidade</h4>
            <h4>Rua: $rua_pessoa</h4>
            <h4>N° da casa: $numero_pessoa</h4>

            <h3>Guade essas informações</h3>
            <br>
        </body>
        </html>
        ";

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("comprovante.pdf", ["Attachment" => false]);

?>
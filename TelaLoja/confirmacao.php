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

        $cadastrador = $_SESSION["cadastrador"];
        $unidades = $_SESSION["quantia"];
        $contato_cadastrador = $_SESSION["contato_cadastrador"];
        $empresa = $_SESSION["fornecedor"];
        $preco = $_SESSION["preco"];

        $html = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>PDF Example</title>

            <link rel='stylesheet' href='../assets/bootstrap-5.3.3-dist/css/bootstrap.css'>
            <link rel='stylesheet' href='../assets/css/basic.css'>
            <link rel='stylesheet' href='../assets/bootstrap-5.3.3-dist/css/bootstrap.css'>

            <link rel='icon' type = 'image/jpeg' href='../assets/img/personalizacao/logo_diminuida.jpeg'>

        </head>
        <body>

            <style>
            
                body{

                    

                }

                

                .tudo{

                    // margin: 20px;
                    // color: rgb(141, 141, 0);
                    
                }

                .valor {

                    display: table;
                    border-style: ridge;
                    border-width: 10px;
                    padding: 10px;
                    justify-content: space-between;
                    width: 100%

                }
                .t1 {
                    text-align: left;
                    display: table-cell;
                }
                .t2 {
                    text-align: right;
                    display: table-cell;
                }

                .princ_comp{

                    text-align: center;

                }

                        
            
            </style>
            <div class = 'tudo'>
                <div class = 'princ_comp'>
                    <h1>A sua compra foi um sucesso!</h1>
                    <h3>O produto $prod_nome será entregue no endereço abaixo.</h5>
                </div>
                <div class = 'dados_loc'>
                    <h4>Estado: $estado</h4>
                    <h4>Cidade: $cidade</h4>
                    <h4>Rua: $rua_pessoa</h4>
                    <h4>N° da casa: $numero_pessoa</h4>
                </div>

                <div class = 'valor'>

                    <h1 class = 't1'>TOTAL:</h1>
                    <h1 class = 't2'>R$ $preco</h1>

                </div>

                <div class = 'dados_prod'>
                    <h4>Nome do cadastrador: $cadastrador</h4>
                    <h4>Contato do cadastrador: $contato_cadastrador</h4>
                    <h4>Nome da fabricante: $empresa</h4>
                    <h4>Quantidade em unidades: $unidades</h4>

                </div>

            </div>
            <script src='../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js'></script>
            <script src='../assets/bootstrap-5.3.3-dist/js/bootstrap.js'></script>

        </body>
        </html>

        ";

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("comprovante.pdf", ["Attachment" => false]);

?>

<div></div>
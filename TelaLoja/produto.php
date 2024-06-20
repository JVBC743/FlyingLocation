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
    $resultado_cep = $banco->buscaSQL("*", "usuarios", "WHERE", "nomePessoa = '$nome_usuario'");

    if ($resultado_cep && mysqli_num_rows($resultado_cep) > 0) {

        $linha = mysqli_fetch_assoc($resultado_cep);

        $cep_pessoa = $linha["cepPessoa"];
        $casa = $linha["numeracao"];
        $cargo = $linha["cargo"];

    }


    if (isset($_SESSION['nome_produto'])) {

        $nome_produto = $_SESSION['nome_produto'];

        $resul = $banco->buscaSQL("*", "produtos", "WHERE", "nome_produto = '$nome_produto'");

        if ($resul && mysqli_num_rows($resul) > 0) {
            $linha = mysqli_fetch_assoc($resul);
            $img_prod = $linha["imagem_produto"];
            $caminho = $linha["caminho"];
            $_SESSION["prod_nome"] = $prod_nome = $linha["nome_produto"];
            $_SESSION["prod_preco"] = $prod_preco = $linha["preco"];
            

        }
    }

    $cep_teste->lerCEP($cep_pessoa);
    $cep_teste->adaptarJson($cliente);
    $logradouro = $cliente->logradouro;

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $prod_nome ?></title>
    <style>
        #mapContainer {
            width: 60%;
            height: 60vh;
            margin: 20px;

        }

        #formContainer {
            margin: 20px;
        }
    </style>
    <script src="https://js.api.here.com/v3/3.1/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://js.api.here.com/v3/3.1/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://js.api.here.com/v3/3.1/mapsjs-ui.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js" type="text/javascript" charset="utf-8"></script>
    
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/basic.css">

</head>

<body>

<?php if($nome_usuario == null): ?>

            <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
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
                            <a class="nav-link active" aria-current="page" href = "editar_prod.php">Meus produtos</a>
                        </li>

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
<!-- ============================================================NAV BAR=================================================================================== -->


        <h1 style = "margin: 20px; "><?php echo $prod_nome; ?></h1>
        <img src="../assets/img/products/<?php echo $img_prod; ?>" style = "margin: 20px; border-style: solid; height: 200px; width: 200px;">
        <p style = "margin: 30px"><?php echo "Preço: R$" . $prod_preco; ?></p>

        <form action = "produto.php" method="post">

            <input type = "submit" name = "comprar", value = "Comprar" >

        </form>


        <?php if (isset($_POST["comprar"])): ?>

        <h3>O produto será entregue no endereço:</h3>
        <br>
        <h3>N° da residência: <?php echo htmlspecialchars($casa); ?></h3>
        <h5><?php echo htmlspecialchars($cliente->logradouro); ?></h5>
        <br>
        <h4>Olhe no mapa e verifique se o endereço está correto. Se não estiver, você pode alterar o valor do endereço nos campos.</h4>

        <form id="addressForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div id="formContainer">
                <label for="rua">Rua:</label>
                <input type="text" id="rua" name="rua" value="<?php echo htmlspecialchars($logradouro); ?>">

                <label for="numero">Número da casa:</label>
                <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($casa); ?>">
                <button type="button" onclick="geocode()">Mostrar no mapa</button>
            </div>
            <input type="submit" name="confirmar_compra" value="Confirmar Compra">
        </form>

        <div id="mapContainer" style="margin-right: 20px;"></div>
        <script>
            var platform = new H.service.Platform({
                'apikey': 'Z7sBZtFTTAwvzbsIAviUxi5KHr4sFrprGz1QayKVhrE',
                'useHTTPS': true,
                'lang': 'pt-BR'
            });

            var maptypes = platform.createDefaultLayers();

            var map = new H.Map(
                document.getElementById('mapContainer'),
                maptypes.vector.normal.map, {
                    zoom: 20,
                    center: {
                        lng: -47.9292,
                        lat: -15.7801
                    }
                });

            var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

            var ui = H.ui.UI.createDefault(map, maptypes);

            function geocode() {
                var street = document.getElementById('rua').value;
                var number = document.getElementById('numero').value;
                var queryString = street + ' ' + number + ', Brasil';

                var geocodingParams = {
                    q: queryString
                };

                var geocoder = platform.getSearchService();

                geocoder.geocode(geocodingParams, onResult, function (e) {
                    alert('Erro na geocodificação: ' + e);
                });
            }

            function onResult(result) {
                var locations = result.items;
                if (locations.length > 0) {
                    var position = locations[0].position;
                    var lat = position.lat;
                    var lng = position.lng;

                    map.setCenter({
                        lat: lat,
                        lng: lng
                    });
                    var marker = new H.map.Marker({
                        lat: lat,
                        lng: lng
                    });
                    map.addObject(marker);
                } else {
                    alert('Nenhuma localização encontrada.');
                }
            }
        </script>
        <?php endif; ?>
        
        <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
        <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
        <script src="../assets/js/modals.js"></script>

    <?php endif; ?>

</body>
</html>

<?php

    if (isset($_POST["confirmar_compra"])) {
        if (isset($_POST["numero"]) && !empty($_POST["numero"]) && isset($_POST["rua"]) && !empty($_POST["rua"])) {

            $_SESSION["numero_pessoa"] = $_POST["numero"];
            $_SESSION["rua_pessoa"] = $_POST["rua"];

            header("Location: confirmacao.php");

            exit(); 
        } else {
            echo "Rua ou número não definidos.";
        }
    }
?>

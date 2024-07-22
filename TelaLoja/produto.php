<?php
    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);


    require("../Adaptador/BDAcesso.php");
    require("../Adaptador/CepAdaptor.php");

    use padroes_projeto\Adaptador\CepAdaptor;
    use padroes_projeto\classes\Cliente;
    use padroes_projeto\Adaptador\BDAcesso;

    $cep_teste = new CepAdaptor;
    $cliente = new Cliente;


    if(isset($_SESSION["nome_usuario"])){

        $nome_usuario = $_SESSION["nome_usuario"];

    }else{
        
        $nome_usuario = null;

    }

    $banco = BDAcesso::getInstance();
    $resultado_cep = $banco->buscaSQL("*", "usuarios", "WHERE", "nome = '$nome_usuario'");

    if ($resultado_cep && mysqli_num_rows($resultado_cep) > 0) {

        $linha = mysqli_fetch_assoc($resultado_cep);

        $cep_pessoa = $linha["cep"];
        $casa = $linha["numero_casa"];
        $cargo = $linha["cargo"];
        $credito = $linha["credito"];
        $imagem_pss = $linha["imagem_pessoa"];

    }


    if (isset($_SESSION['nome_produto'])) {

        $nome_produto = $_SESSION['nome_produto'];

        $resul = $banco->buscaSQL("*", "produtos", "WHERE", "nome_produto = '$nome_produto'");

        if ($resul && mysqli_num_rows($resul) > 0) {
            $linha = mysqli_fetch_assoc($resul);
            $img_prod = $linha["imagem_produto"];
            $quantidade = $linha["quantidade"];

            $cadastrador = $linha["cadastrador"];

            $cont_cadastrador = $banco->buscaSQL("email", "usuarios", "WHERE", "nome = '$cadastrador'");

            if($cont_cadastrador && mysqli_num_rows($cont_cadastrador) > 0){

                $lin = mysqli_fetch_assoc($cont_cadastrador);

                $email = $lin["email"];
            }

            $fornecedor = $linha["fornecedor"];
            $garantia = $linha["garantia"];
            $estoque = $linha["quantidade"];

            
            $_SESSION["prod_nome"] = $prod_nome = $linha["nome_produto"];
            $_SESSION["prod_preco"] = $prod_preco = $linha["preco"];

        }
    }

    if(isset($_POST["sair"])){
        
        session_destroy(); // Destrói a sessão

        header("Location: ../index.php"); // 
        exit();
    }

    

    $cep_teste->lerCEP($cep_pessoa);
    $cep_teste->adaptarJson($cliente);
    $logradouro = $cliente->logradouro;
    $estado = $cliente->uf;
    $cidade = $cliente->localidade;

    switch($estado) {
        case 'AC':
            $estado = "Acre";
            break;
        case 'AL':
            $estado = "Alagoas";
            break;
        case 'AP':
            $estado = "Amapá";
            break;
        case 'AM':
            $estado = "Amazonas";
            break;
        case 'BA':
            $estado = "Bahia";
            break;
        case 'CE':
            $estado = "Ceará";
            break;
        case 'DF':
            $estado = "Distrito Federal";
            break;
        case 'ES':
            $estado = "Espírito Santo";
            break;
        case 'GO':
            $estado = "Goiás";
            break;
        case 'MA':
            $estado = "Maranhão";
            break;
        case 'MT':
            $estado = "Mato Grosso";
            break;
        case 'MS':
            $estado = "Mato Grosso do Sul";
            break;
        case 'MG':
            $estado = "Minas Gerais";
            break;
        case 'PA':
            $estado = "Pará";
            break;
        case 'PB':
            $estado = "Paraíba";
            break;
        case 'PR':
            $estado = "Paraná";
            break;
        case 'PE':
            $estado = "Pernambuco";
            break;
        case 'PI':
            $estado = "Piauí";
            break;
        case 'RJ':
            $estado = "Rio de Janeiro";
            break;
        case 'RN':
            $estado = "Rio Grande do Norte";
            break;
        case 'RS':
            $estado = "Rio Grande do Sul";
            break;
        case 'RO':
            $estado = "Rondônia";
            break;
        case 'RR':
            $estado = "Roraima";
            break;
        case 'SC':
            $estado = "Santa Catarina";
            break;
        case 'SP':
            $estado = "São Paulo";
            break;
        case 'SE':
            $estado = "Sergipe";
            break;
        case 'TO':
            $estado = "Tocantins";
            break;
        default:
            $estado = "Estado desconhecido";
            break;
    }


    $quant_total_prod = $banco->buscaSQL("quantidade", "produtos", "WHERE", "nome_produto = '$nome_produto'");

    if($quant_total_prod && mysqli_num_rows($quant_total_prod) > 0){

        $ln_prod = mysqli_fetch_assoc($quant_total_prod);
        $quant_tot = $ln_prod["quantidade"];

    }else{

        echo "<script>window.alert('erro na consulta ')</script>";

    }

    if (isset($_POST["confirmar_compra"])) {

        echo "<script>console.log('Crédito: $credito, Preço do produto: $prod_preco');</script>";
    
        if ($credito < $prod_preco) {
            echo "<script>window.alert('Você não tem grana para isso.')</script>";
        } elseif (isset($_POST["numero"]) && !empty($_POST["numero"]) && isset($_POST["rua"]) && !empty($_POST["rua"])) {
    
            if (isset($_POST["quantia"])) {
                
                $quantia = $_POST["quantia"];

              
    
                $quant_total_prod = $banco->buscaSQL("quantidade", "produtos", "WHERE", "nome_produto = '$nome_produto'");
    
                if ($quant_total_prod && mysqli_num_rows($quant_total_prod) > 0) {
                    $ln_prod = mysqli_fetch_assoc($quant_total_prod);
                    $quant_tot = $ln_prod["quantidade"];
                    echo "<script>console.log('Quantidade total do produto: $quant_tot');</script>";
                } else {
                    echo "<script>window.alert('Produto não encontrado ou sem estoque.')</script>";
                    exit();
                }

                if(empty($quantia)){

                    $_SESSION["erro"] = "Especifique a quantidade";
                    header("Location: produto.php");
                    echo "<script>window.alert('Especifique a quantidade desejada.')</script>";


                }
    
                if ($quantia <= $quant_tot) {

                    $preco_multiplicado = $quantia * $prod_preco;

                    if($preco_multiplicado > $credito){


                        echo "<script>window.alert('Preço maior do que o crédito.')</script>";

                        header("Location: produto.php");


                    }
                    $subtracao = $credito - $preco_multiplicado;
                    $quantidade = $quant_tot - $quantia;
    
                    echo "<script>window.alert('Cheguei aqui 1')</script>";
    
                    $update_prod = $banco->atualizarDados("produtos", "quantidade = $quantidade", "WHERE", "nome_produto = '$nome_produto'");
                    if ($update_prod) {
                        echo "<script>console.log('Quantidade do produto atualizada com sucesso');</script>";
                    } else {
                        echo "<script>window.alert('Erro ao atualizar a quantidade do produto.')</script>";
                        exit();
                    }
    
                    echo "<script>window.alert('Cheguei aqui 2')</script>";
    
                    $update_user = $banco->atualizarDados("usuarios", "credito = $subtracao", "WHERE", "nome = '$nome_usuario'");
                    if ($update_user) {
                        echo "<script>console.log('Crédito do usuário atualizado com sucesso');</script>";
                    } else {
                        echo "<script>window.alert('Erro ao atualizar o crédito do usuário.')</script>";
                        exit();
                    }

                    $busca_dinheiro = $banco->buscaSQL("credito", "usuarios", "WHERE", "nome = '$cadastrador'");

                    if($busca_dinheiro && mysqli_num_rows($busca_dinheiro) > 0){

                        $linha_cred = mysqli_fetch_assoc($busca_dinheiro);
                        $credito_cadastrador = $linha_cred["credito"];
                    }

                    $soma = $credito_cadastrador + $preco_multiplicado;

                    $update_recebimento = $banco->atualizarDados("usuarios", "credito = $soma", "WHERE", "nome = '$cadastrador'");

                    
                    
                    $_SESSION["numero_pessoa"] = $_POST["numero"];
                    $_SESSION["rua_pessoa"] = $_POST["rua"];
                    $_SESSION["estado"] =  $estado;
                    $_SESSION["cidade"] = $cidade;

                    $_SESSION["cadastrador"] = $cadastrador;
                    $_SESSION["quantia"] = $quantia;
                    $_SESSION["contato_cadastrador"] = $email;
                    $_SESSION["fornecedor"] = $fornecedor;
                    $_SESSION["preco"] = $preco_multiplicado;
                    
                    echo "<script>window.alert('Cheguei aqui 4')</script>";
    
                    header("Location: confirmacao.php");
                    exit();
                } else {
                    echo "<script>window.alert('Quantidade maior do que o estoque disponível')</script>";
                }
            } else {
                echo "<script>window.alert('Quantidade desejada não foi recebida.')</script>";
            }
        } else {
            echo "<script>window.alert('Rua ou número não definidos.')</script>";
        }
    }

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
    <link rel="icon" type = "image/jpeg" href="../assets/img/personalizacao/logo_diminuida.jpeg">


</head>

<body>

<?php if($nome_usuario == null): ?>

            <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
            <a href = "../index.php">Voltar</a>

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
                            <a class="nav-link active" aria-current="page" href="../TelaLoja/loja.php">Loja</a>
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
                            <a class="nav-link" aria-current="page" href = "../TelaLoja/lista_produtos.php">Meus Produtos</a>
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

                                <?php 

                                if(isset($_POST["sair"])){
                                    session_start();
                                    session_unset(); // Limpa todas as variáveis de sessão
                                    session_destroy(); // Destrói a sessão

                                    header("Location: login.php"); // Redireciona para a página de login
                                    exit();
                                                                    }

                                ?>
                          </form>
                            <?php 
                                if(isset($_POST["perfil"])){

                                    if(isset($_SESSION["editar_usuario"])){

                                        unset($_SESSION["editar_usuario"]);
                                    }

                                    header("Location: ../tela/perfil.php");

                                }
                                if(isset($_POST["sair"])){
                                    session_destroy();
                                    session_unset();

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
        <div class="prod_info d-flex justify-content-center align-items-center">
            <div class="d-flex flex-column align-items-center">
                <h1 style = "margin: 20px; "><?php echo $prod_nome; ?></h1>
                <img src="../assets/img/products/<?php echo $img_prod; ?>" style = "margin: 20px; border-style: solid; height: 200px; width: 200px;">
                <form action="<?php $_SERVER["PHP_SELF"]; ?>" method = "post">
                    <input class="btn btn-primary" type="submit" name = "comprar" value = "Comprar">
                </form>
            </div>
            <div class="d-flex flex-column">
                <p class="border border-primary" style = "margin: 30px"><?php echo "Preço: R$" . $prod_preco; ?></p>
        
                <p style = "margin: 30px" class="border border-primary"> <?php echo "Garantia: " . $garantia . " dias."; ?></p>
                <!-- adicionar garantia em formato de ano, mes, dias. -->
        
        
                <p style = "margin: 30px" class="border border-primary"> <?php echo "Fornecedor(a): " . $fornecedor; ?></p>
        
                <p style = "margin: 30px" class="border border-primary"> <?php echo "Cadastrador: " . $cadastrador; ?></p>
        
                <!-- <p style = "margin: 30px"> <?php //echo "Contato do cadastrador: " . //$contato_cadastrador; ?></p> -->
        
                <p style = "margin: 30px" class="border border-primary"><?php echo "Contato do cadastrador: " . $email; ?></p>
        
                <p style = "margin: 30px" class="border border-primary"><?php echo "Quantidade disponível: " . $estoque . " unidades"; ?></p>
                
                <?php 

                if(isset($_SESSION['erro'])){
                
                    echo "<script> window.alert('Especifique a quantidade')</script>";

                    unset($_SESSION["erro"]);

                }
                
                
                ?>
            </div>
        </div>

    
        <?php if (isset($_POST["comprar"])): ?>
        
            <div class="container text-center">
                <div class="row align-items-start">
                    <div class="col">
                        <h3>O produto será entregue no endereço:</h3>
                        <br>
                        <h3>N° da residência: <?php echo htmlspecialchars($casa); ?></h3>
                        <h5><?php echo htmlspecialchars($cliente->logradouro); ?></h5>
                    </div>
                    <div class="col">
                        <form id="addressForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div id="formContainer">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="">Rua:</span>
                                    <input type="text" class="form-control" id="rua" name="rua" value="<?php echo htmlspecialchars($logradouro); ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="">Número da casa:</span>
                                    <input type="text" class="form-control" id="numero" name="numero" value="<?php echo htmlspecialchars($casa); ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="">Estado:</span>
                                    <input type="text" class="form-control" id="estado" name="estado" value="<?php echo htmlspecialchars($estado); ?>">
                                </div>
                                <button type="button" class="btn btn-primary mb-3" onclick="geocode()">Mostrar no mapa</button>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="">Quantidade desejada:</span>
                                    <input type = "number" class="form-control" name = "quantia">
                                </div>
                                <input type="submit" class="btn btn-primary" name="confirmar_compra" value="Confirmar Compra">
                            </div>                           

                        </form>
                        
                    </div>
                </div>
                <h4>Olhe no mapa e verifique se o endereço está correto. Se não estiver, você pode alterar o valor do endereço nos campos.</h4>
                    <div id="mapContainer" class="mx-auto">
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
                                var state = document.getElementById('estado').value;

                                var queryString = street + ' ' + number + ' ' + state + ', Brasil';

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
                    </div>
            </div>



            <!-- <div class="prod_info d-flex justify-content-center align-items-center">
                <div class="d-flex flex-column align-items-center">
                    
                    <div class="d-flex flex-row">
                        
                    </div>
                    
                </div> -->

                

                
        <?php endif; ?>
        
        <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
        <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
        <script src="../assets/js/modals.js"></script>



    <?php endif; ?>

</body>
</html>
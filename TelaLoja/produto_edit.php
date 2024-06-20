<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once "../Adaptador/BDAcesso.php";// Dessa forma está correta.
    use padroes_projeto\Adaptador\BDAcesso;
    $banco = BDAcesso::getInstance();


    if(isset($_SESSION["nome_produto"])){
        $nome_produto = $_SESSION["nome_produto"];
        $respostaSQL = $banco->buscaSQL("*", "produtos", "WHERE", "nome_produto = '$nome_produto'");
        if($respostaSQL){
            $dadosProduto = mysqli_fetch_assoc($respostaSQL);
            list($ano, $mes, $dia) = explode('-', $dadosProduto["fabricacao"]);
            $data = date('d-m-Y', strtotime("$dia-$mes-$ano"));
            $data = str_replace("-","/", $data);
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nome_produto; ?></title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/basic.css">
</head>
<body>
    <?php if($nome_produto == null): ?> <!--//OLHA O IFFFF-->

    <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
    <h1>Ou o produto que você acessou não existe.</h1><br>
    <a href = "../index.php">Voltar</a>

    <?php else: ?>

        <h1><?php echo $nome_produto; ?></h1>
        <div class="center">
            <form action = "produto_edit.php" method = "post" enctype="multipart/form-data"><!--Tem que colocar o UPDATE no campo da quantidade, para ser dinâmico-->

                <div class="input-group mb3" id="width-30rem">
                        <span class="input-group-text" id="basic-addon1">Nome</span>
                        <input type="text" class="form-control" name = "nome_produto" value="<?php echo $dadosProduto['nome_produto'] ?>" placeholder="Abacate" aria-label="Abacate" aria-describedby="basic-addon1">
                </div>
                <br>
                <div class="input-group mb3" id="width-30rem">
                        <span class="input-group-text" id="basic-addon1">Preço:</span>
                        <input type="text" class="form-control" name = "preco" value="<?php echo $dadosProduto['preco'] ?>" placeholder="120,20" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <br>
                <div class="input-group mb3" id="width-30rem">
                        <span class="input-group-text" id="basic-addon1">Quantidade:</span>
                        <input type="text" class="form-control" name = "quantidade" value="<?php echo $dadosProduto['quantidade'] ?>" placeholder="1, 2 ou infinito" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <br>
                <div class="input-group mb3" id="width-30rem">
                        <span class="input-group-text" id="basic-addon1">Fornecedor</span>
                        <input type="text" class="form-control" name = "fabricante" value="<?php echo $dadosProduto['fornecedor'] ?>" placeholder="Gorginho da Silva" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <br>
                <div class="input-group mb3" id="width-30rem">
                        <span class="input-group-text" id="basic-addon1">Data de fabricação</span>
                        <input type="text" class="form-control" name = "data_fabrica" value="<?php echo $data ?>" placeholder="01/03/1943" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <br>
                <div class="input-group mb3" id="width-30rem">
                        <span class="input-group-text" id="basic-addon1">Garantia:</span>
                        <input type="text" class="form-control" name = "garantia" value="<?php echo $dadosProduto['garantia'] ?>" placeholder="quantidade de em dias ex: 12"aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <br>
                <div class="input-group mb3" id="width-30rem">
                        <span class="input-group-text" id="basic-addon1">Descrição:</span>
                        <input type="text" class="form-control" name = "descricao" value="<?php echo $dadosProduto['descricao'] ?>" placeholder="Esse produto tem tantas qualidades" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <br>
                <div class="input-group mb3" id="width-30rem">
                        <span class="input-group-text" id="basic-addon1">Arquivo:</span>
                        <input type="file" class="form-control" name = "arquivoEnviado"  aria-label="Username" aria-describedby="basic-addon1"                                                                  laceholder="nig-">
                </div>
                <button id="AbrirModal" type = "button" class="btn btn-success">Imagem Atual</button>
                <dialog id="modal">
                        <img src="../assets/img/products/<?php echo $dadosProduto["imagem_produto"] ?>" alt="img1">
                        <button type="button" id="FechaModal" >Fechar</button>
                </dialog>
                <br>
                <div class="center-item">
                    <input type = "submit" name = "atualizar" value = "Atualizar" class = "btn btn-primary">
                    <a type="button" class = "btn btn-danger" href = "editar_prod.php">Cancelar</a>
                </div>
            </form>
        </div>

    <?php endif; ?>


    
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
    
</body>
</html>

<?php


$usuario = $_SESSION["nome_usuario"];

/*
Uma ideia que seria interessante é, ao invés de aplicar um montão de "ifs",  aplicar os operadores ternários. 

Outra ideia é se, caso o campo de fornecedor estiver vazio, o sistema automaticamente associar o nome do usuário
logado ao produto.
*/

    if(isset($_POST["atualizar"])){
        if(isset($_POST["nome_produto"]) && !empty($_POST["nome_produto"])){
            if(isset($_POST["preco"]) && !empty($_POST["preco"])){
                if(isset($_POST["quantidade"]) && !empty($_POST["quantidade"])){
                    if(isset($_POST["fabricante"]) && !empty($_POST["fabricante"])){
                        if(isset($_POST["data_fabrica"]) && !empty($_POST["data_fabrica"])){
                            if(isset($_POST["garantia"]) && !empty($_POST["garantia"])){
                                if(isset($_POST["descricao"]) && !empty($_POST["descricao"])){
                                    
                                    $nome = $_POST["nome_produto"];
                                    $preco = $_POST["preco"];
                                    $quant = $_POST["quantidade"];
                                    $fabri = $_POST["fabricante"];
                                    $data2 = $_POST["data_fabrica"];
                                    $garan = $_POST["garantia"];
                                    $descri = $_POST["descricao"];

                                    list($dia, $mes, $ano) = explode('/', $data);

                                    $data = date('Y-m-d', strtotime("$ano-$mes-$dia"));

                                    $preco = str_replace(",",".", $preco);

                                    $banco = BDAcesso::getInstance();

                                    $sets = "nome_produto='$nome', preco=$preco, quantidade=$quant, fornecedor='$fabri', fabricacao='$data', garantia=$garan, descricao='$descri'";
                                    $nomeAntigo = $dadosProduto["nome_produto"];

                                    $inst = $banco->atualizarDados("produtos", $sets, "WHERE", "nome_produto = '$nomeAntigo'");
                                         
                                    $_SESSION["nome_produto"] = $nome;


                                    if($inst){
                                        echo "Dados cadastrados com sucesso!";
                                    }else{
                                        echo "Houve algum erro no processo de cadastramento.";
                                    }
                                    
                                
                                    if (isset($_FILES["arquivoEnviado"]) && $_FILES["arquivoEnviado"]["size"] > 0){
                                        
                                        $targetDir = "../assets/img/products/"; 
                                        $fileName = basename($_FILES["arquivoEnviado"]["name"]);
                                        $targetFile = $targetDir . $fileName;
                                        $uploadOk = 1;
                        
                                        $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
                                        if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif" ) {
                                            echo "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
                                            $uploadOk = 0;
                                        }

                                        //É aqui que ele verifica se a imagem já está no sistema. A não ser que essa parte não seja isso.
                                        if (file_exists($targetFile)) {
                                            echo "Desculpe, o arquivo já existe.";
                                            $uploadOk = 0;
                                        }
                        
                                        if ($_FILES["arquivoEnviado"]["size"] > 500000) {
                                            echo "Desculpe, o arquivo é muito grande.";
                                            $uploadOk = 0;
                                        }
                                        if ($uploadOk == 1) {
                                            if (move_uploaded_file($_FILES["arquivoEnviado"]["tmp_name"], $targetFile)) {
                                                echo "O arquivo " . basename($_FILES["arquivoEnviado"]["name"]) . " foi enviado com sucesso.";
                                            } else {
                                                echo "Desculpe, ocorreu um erro ao enviar o arquivo.";
                                            }
                                        }

                                        

                                        //O "inserirDados()" é do tipo "void", então seria interessante apenas chamar esse método sozinho.
                                        //Sem atribuir à nenhuma variável. Ou, então, aplicar algum retorno à esse método.
                                        $sets = "nome_produto='$nome', preco=$preco, quantidade=$quant, fornecedor='$fabri', fabricacao='$data', garantia=$garan, descricao='$descri', imagem_produto='$fileName'";
                                        $nomeAntigo = $dadosProduto["nome_produto"];

                                        $inst = $banco->atualizarDados("produtos", $sets, "WHERE", "nome_produto = '$nomeAntigo'");
                                            
                                        if(!$inst){
                                            echo "Dados cadastrados com sucesso!";
                                        }else{
                                            echo "Houve algum erro no processo de cadastramento.";
                                        }
                                    }else{
                                        
                                    }
                                }else{

                                    echo "Descrição não inserida!";
                                }   
                            }else{
                                echo "Dias de garantia não inserido!";
                            }
                        }else{
                            echo "Data de fabricação não inserida!";
                        }
                    }else{
                        echo "Nome do fabricante não inserido! Ou não registrado no nosso sistema!";
                    }
                }else{
                    echo "Quantidade não inserida!";
                }
            }else{
                echo "Preço não inserido!";
            }
        }else{
            echo "Nome não inserido!";
        }   
    }

?>

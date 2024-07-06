<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require("classes/Email.php");
    use email\Email;

    $email = new Email();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste</title>


    <style>
        #textarea{

            width: 500px;
            height: 400px;

        }

    </style>
</head>
<body>

    <form action="teste_email.php" method = "post">
        
        <label>Assunto:</label>
        <input type="text" name = "assunto"><br>

        </label>Conteúdo: </label><br>
        <textarea id = "textarea" name = "conteudo"></textarea><br>

        </label>Destino: </label>
        <input type="text" name = "destino"><br>

        </label>Conteúdo alternativo: </label>
        <input type="textarea" name = "alt"><br>

        <input type="submit" name = "botao">

    </form>

    <?php

        if(isset($_POST["botao"])){
            if(isset($_POST["assunto"])){
                if(isset($_POST["conteudo"])){
                    if(isset($_POST["destino"])){
                        if(isset($_POST["alt"])){

                            $assunto = $_POST["assunto"];
                            $conteudo = $_POST["conteudo"];
                            $destino = $_POST["destino"];
                            $alt = $_POST["alt"];

                            $envio = $email->enviarEmail($assunto, $conteudo, $destino, $alt);

                            if($envio == false){

                                echo "Falha no envio do e-mail";
                            }else{

                                echo "E-mail enviado com sucesso!";
                            }
                        }
                    }
                }
            }
        }
    ?>
    
</body>
</html>


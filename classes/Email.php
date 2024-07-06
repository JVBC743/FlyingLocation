<?php

    namespace email;

    require_once ("../email/vendor/autoload.php");
    require_once("../Adaptador/BDAcesso.php");
    use padroes_projeto\Adaptador\BDAcesso;
    use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public function emailGenerico(string $assunto, string $conteudo, string $alt, string $email_destino, $tok = null, $id_usuario = null){

        $mail = new PHPMailer();
        $banco = BDAcesso::getInstance();

        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'flyinglocation2902@outlook.com';
        $mail->Password = 'FlyLoc@2902';
        $mail->Port = 587;

        $mail->setFrom('flyinglocation2902@outlook.com', 'JVBC/JCMS Co.');
        $mail->addAddress($email_destino);

        $mail->isHTML(true);

        $mail->Subject = $assunto;

        //Esse alt é a versão simplificada do e-mail, caso a plataforma não suporte e-mails com HTML.

        if($tok == 'token' && $id_usuario){

            $token = uniqid();

            $link_token = 'http://201.2.18.191:2222/FlyingLocation/tela/pegar_token.php?token=' . $token;

            $insercao = $banco->inserirDados("tokens", "'$id_usuario', '$token', NOW() + INTERVAL 1 HOUR", "id_usuario, valor_token, data_expiracao");

            if($insercao == false){

                echo "Houve um erro na inserção";
    
            }else{

                $mail->Body = nl2br($conteudo . " <a href = '$link_token'>clique aqui</a>" );
                $mail->AltBody = nl2br(strip_tags($alt . "<a href = '$link_token'>clique aqui</a>"));

            }

        }else if($tok == 'usuario'){//TESTADO

            $token = uniqid();

            $link_token = 'http://201.2.18.191:2222/FlyingLocation/tela/pegar_token.php?token=' . $token;


            $insercao = $banco->inserirDados("tokens", "'$token', NOW() + INTERVAL 1 HOUR, '$email_destino'", "valor_token, data_expiracao, email");

            if($insercao == false){

                echo "Houve um erro na inserção";
    
            }else{

                $mail->Body = nl2br($conteudo . " <a href = '$link_token'>clique aqui</a>" );
                $mail->AltBody = nl2br(strip_tags($alt . "<a href = '$link_token'>clique aqui</a>"));


            }

        }else{

            $mail->Body = nl2br($conteudo);

        }

        if(!$mail->send()) {
        
            return false;

        } else {
            return true;
        
        }
    }
}


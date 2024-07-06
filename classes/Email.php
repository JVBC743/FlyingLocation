<?php

    namespace email;

    use PHPMailer\PHPMailer\PHPMailer;
    require ("email/vendor/autoload.php");

class Email{


    public function emailGenerico(string $assunto, string $conteudo, string $destino, string $alt){

        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'flyinglocation2902@outlook.com';
        $mail->Password = 'FlyLoc@2902';
        $mail->Port = 587;

        $mail->setFrom('flyinglocation2902@outlook.com', 'JVBC/JCMS Co.');
        $mail->addAddress($destino);

        $mail->isHTML(true);

        $mail->Subject = $assunto;

        $mail->Body = nl2br($conteudo);

        $mail->AltBody = nl2br(strip_tags($alt));
        //Esse alt é a versão simplificada do e-mail, caso a plataforma não suporte e-mails com HTML.

        if(!$mail->send()) {
            //echo 'Não foi possível enviar a mensagem.';
            //echo 'Erro: ' . $mail->ErrorInfo;

            return false;

        } else {
            //echo "E-mail enviado com sucesso!!!<br>";
            return true;
        }
    }

    public function emailComToken(string $assunto, string $conteudo, string $destino, string $alt){

        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'flyinglocation2902@outlook.com';
        $mail->Password = 'FlyLoc@2902';
        $mail->Port = 587;

        $mail->setFrom('flyinglocation2902@outlook.com', 'JVBC/JCMS Co.');
        $mail->addAddress($destino);

        $mail->isHTML(true);

        $mail->Subject = $assunto;

        $mail->Body = nl2br($conteudo);

        $mail->AltBody = nl2br(strip_tags($alt));
        //Esse alt é a versão simplificada do e-mail, caso a plataforma não suporte e-mails com HTML.

        $link_token = 'http://201.2.18.191:2222/FlyingLocation/tela/pegar_token.php?token=' . $token;

        //VOU PENSAR UM POUCO MAIS SOBRE ESSE MÉTODO. TALVEZ UM RETORNO COM O LINK?

        if(!$mail->send()) {
            //echo 'Não foi possível enviar a mensagem.';
            //echo 'Erro: ' . $mail->ErrorInfo;

            return false;

        } else {
            //echo "E-mail enviado com sucesso!!!<br>";
            return true;
        }
    }

    
}


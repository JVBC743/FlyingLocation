<?php
require 'PDF/vendor/autoload.php';

use Dompdf\Dompdf;

// Instanciar o Dompdf
$dompdf = new Dompdf();

// Capturar o conteúdo HTML que você deseja converter em PDF
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Example</title>
</head>
<body>
    <h1>Olá, Mundo!</h1>
    <p>Este é um exemplo de PDF gerado a partir de HTML.</p>
</body>
</html>
';

// Carregar o HTML no Dompdf
$dompdf->loadHtml($html);

// (Opcional) Definir o tamanho do papel e a orientação
$dompdf->setPaper('A4', 'portrait');

// Renderizar o HTML como PDF
$dompdf->render();

// Enviar o PDF para o navegador
$dompdf->stream("relatorio.pdf", ["Attachment" => false]);
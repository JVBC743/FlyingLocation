<?php 

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require 'PDF/vendor/autoload.php';

    use Dompdf\Dompdf;

    $dompdf = new Dompdf();


    $html = "

        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Align Divs Side by Side</title>
            <style>
                .container {
                    display: table;
                    width: 100%; /* Adjust the width as needed */
                }
                .box {
                    display: table-cell;
                    width: 45%; /* Adjust the width as needed */
                    margin: 10px;
                    padding: 10px;
                    border: 1px solid #ddd;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='box'>Box 1</div>
                <div class='box'>Box 2</div>
            </div>
        </body>
        </html>


    ";

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('comprovante.pdf', ['Attachment' => false]);


?>
<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="../assets/bootstrap-5.3.3-dist/css/bootstrap.css">
</head>
<body>

    <?php if(!isset($_SESSION["nome_usuario"])): ?> <!--//OLHA O IFFFF-->

    <h1>Você tentou acessar a página da loja sem estar logado.</h1><br>
    <a href = "../index.php">Voltar</a>

    <?php else: ?>


        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Disciplina</th>
                    <th scope="col">Curso</th>
                    <th scope="col">Período</th>
                    <th scope="col">Turno</th>
                    <th scope="col">Livre</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a role="button" class="btn btn-primary" >Editar</a>
                            <a role="button" class="btn btn-danger" >Apagar</a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
    <script src="../assets/bootstrap-5.3.3-dist/css/bootstrap.css"></script>
</body>
</html>
<?php

    declare(strict_types=1);

    namespace padroes_projeto\exemploAdapter\adapter\classes;

    class BibliotecaAntiga
    {
        public function salvarNoBanco(){
            echo "Dado salvo no banco de dados!";
        }
        public function gerarRelatorioTXT($name){
            echo "Relatorio gerado: $name.txt";
        }
    }
?>
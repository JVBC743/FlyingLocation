<?php

    declare(strict_types=1);

    namespace padroes_projeto\exemploAdapter\adapter\classes;


    class BibliotecaNova
    {
        public function salvarNoBanco(){
            echo "Dado salvo no banco de dados! | BibliotecaNova";
        }
        public function gerarRelatorioTXT($name){
            echo "Relatorio gerado: $name.txt";
        }
    }
?>
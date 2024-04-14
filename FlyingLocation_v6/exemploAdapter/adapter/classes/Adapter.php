<?php

    declare(strict_types=1);

    namespace padroes_projeto\exemploAdapter\adapter\classes;

    require_once ('classes/BibliotecaNova.php');
    require_once ('interfaces/MetodosInterfaces.php');

    use padroes_projeto\exemploAdapter\adapter\interfaces\MetodosInterfaces;
    use padroes_projeto\exemploAdapter\adapter\classes\BibliotecaNova;

    class Adapter extends BibliotecaNova implements MetodosInterfaces
    {
        public function metodo1(){
            $this->salvarNoBanco();
        }
        public function metodo2($name){
            $this->gerarRelatorioTXT($name);
        }
    }
?>
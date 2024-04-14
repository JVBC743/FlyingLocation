<?php

    declare(strict_types=1);
      
    namespace padroes_projeto\classes;

    class Cliente{

        public $name;
        public $password;
        public $cep;
        public $logradouro;
        public $complemento;
        public $bairro;
        public $localidade;
        public $uf;

        public function exibir_dados(){
            
            echo $this->name . "<br>";
            echo $this->cep . "<br>";
            echo $this->logradouro . "<br>";
            echo $this->complemento . "<br>";
            echo $this->bairro . "<br>";
            echo $this->localidade . "<br>";
            echo $this->uf . "<br>";

        }
    }

?>
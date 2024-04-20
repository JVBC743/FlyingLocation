<?php

  declare(strict_types=1);
      
  namespace padroes_projeto\interfaces;

  interface iCep {

    public function lerCEP($cep);

    public function adaptarJson($cliente);

    public function exibirDados();
  }

?>
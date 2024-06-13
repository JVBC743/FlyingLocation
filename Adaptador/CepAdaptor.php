<?php
  declare(strict_types=1);

  namespace padroes_projeto\Adaptador;

  require_once("../interfaces/iCep.php");
  use padroes_projeto\interfaces\iCep;
  require_once("../classes/Cliente.php");
  use padroes_projeto\classes\Cliente;
 
  class CepAdaptor implements iCep{

    private $cepJson;

     public function lerCEP($cep){
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $this->cepJson = file_get_contents($url);
        if ($this->cepJson === false) {
            echo "Erro ao acessar a API ViaCEP.";
            return;
        }
        
        $data = json_decode($this->cepJson, true);

        if ($data === null) {
            echo "Erro ao decodificar o JSON.";
            return;
        }

        if (isset($data["erro"])) {
            echo "CEP não encontrado.";
            return;
        }
    }

    public function adaptarJson($cliente){
        $data = json_decode($this->cepJson, true);
        $cliente->cep =  $data["cep"];
        $cliente->logradouro =  $data["logradouro"];
        $cliente->complemento =  $data["complemento"];
        $cliente->bairro =  $data["bairro"];
        $cliente->localidade =  $data["localidade"];
        $cliente->uf = $data["uf"];
    }

    public function exibirDados(){
        $data = json_decode($this->cepJson, true);
        echo "CEP: " . $data["cep"] . "<br>";
        echo "Logradouro: " . $data["logradouro"] . "<br>";
        echo "Complemento: " . $data["complemento"] . "<br>";
        echo "Bairro: " . $data["bairro"] . "<br>";
        echo "Cidade: " . $data["localidade"] . "<br>";
        echo "UF: " . $data["uf"] . "<br>";
    }
}

?>
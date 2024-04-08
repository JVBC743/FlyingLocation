<?php

// $conexao = mysqli_connect($banco_servidor, $banco_usuario, $banco_senha, $banco_nome);

// if (!$conexao) {

//     die("Falha na conexão: " . mysqli_connect_error());

// } else {

//  echo "conexão bem-sucedida";
   
// }

namespace padroes_projeto\Adaptador;

class BDAcesso{

    private $banco_servidor = "localhost";
    private $banco_usuario = "jvbc";
    private $banco_senha = "4426655xara";
    private $banco_nome = "MeuBanco";
    private $conexao;
    private static $instancia;

    private function __construct(){

        $this->conexao = mysqli_connect($this->banco_servidor, $this->banco_usuario, $this->banco_senha, $this->banco_nome);
        if (!$this->conexao) {
            die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
        }
    }

    public static function getInstance(){
        if(self::$instancia == NULL){
            self::$instancia = new self;
        }
        return self::$instancia;
    }

    public function buscaSQL($coluna, $tabela, $tipoCondicao = null, $condicao = null){

        if ($tipoCondicao && $condicao) {
            // if($tipoCondicao == "LIKE"){

            //     $sql = "SELECT $coluna FROM $tabela LIKE $condicao";

            // }

            $sql = "SELECT $coluna FROM $tabela $tipoCondicao $condicao";    
            $res = mysqli_query($this->conexao, $sql);

            if ($res) {

                return $res;

            } else {

            echo "Busca não funcionou: " . mysqli_error($this->conexao);

            return false;

            }
            
        } else {
            
            $sql = "SELECT $coluna FROM $tabela;";

            $res = mysqli_query($this->conexao, $sql);

            if ($res) {

                return $res;

            } else {

            echo "Busca não funcionou: " . mysqli_error($this->conexao);

            return false;
            }
        }
    }

    public function inserirDados($tabela, $valores, $colunas = null){

        if($colunas){

            $sql = "INSERT INTO $tabela ($colunas) VALUES($valores)";
        }
        else{
            $sql = "INSERT INTO $tabela VALUES($valores)";
            
        }
        $res = mysqli_query($this->conexao, $sql);
        if($res){

            echo "Dados inseridos";

        }else{

            echo "B-A-N-I-D-O";

        }
    }
}
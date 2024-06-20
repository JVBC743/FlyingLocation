<?php

namespace padroes_projeto\Adaptador;

class BDAcesso{

    private $banco_servidor = "localhost";
    private $banco_usuario = "jvbc";
    private $banco_senha = "4426655xara";
    private $banco_nome = "FlyLoc";
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
            
        }else{
            
            $sql = "INSERT INTO $tabela VALUES($valores)";
            
        }

        $res = mysqli_query($this->conexao, $sql);

        if($res){

            return true;

        }else{

            echo "Erro ao inserir dados: ". mysqli_error($this->conexao);
            return false;

        }
    }

    public function atualizarDados($tabela, $sets, $tipoCondicao, $condicao){

        $sql = "UPDATE $tabela SET $sets $tipoCondicao $condicao";
        
        $res = mysqli_query($this->conexao, $sql);

        if($res){
            return true;
        }else{
            return false;
        }
    }

    public function excluirDados($tabela, $tipoCondicao, $condicao){

        $sql = "DELETE FROM $tabela $tipoCondicao $condicao";

        $res = mysqli_query($this->conexao, $sql);

        if($res){
            
            return $res;
        
        }else{

            echo "Ocorreu um erro ao excluir o dado.";
        }

    }
}
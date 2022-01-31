<?php

namespace App\Models;

require_once __DIR__.'/../config.php';
class GeradorDeSenha
{
    private $tabela_equivalencia = [
        'a'=>'%',
        'b'=>'$',
        'c'=>'@',
        'd'=>'&',
        'e'=>'!',
        'f'=>'*',
        'g'=>'¢',
        'h'=>'|',
        'i'=>'-',
        'j'=>'+',
        'k'=>'=',
        'l'=>'/',
        'm'=>'_',
        'n'=>'?',
        'o'=>'#',
        'p'=>'{',
        'q'=>'}',
        'r'=>'[',
        's'=>']',
        't'=>'¬',
        'u'=>'¨',
        'v'=>')',
        'w'=>'(',
        'x'=>'>',
        'y'=>'^',
        'z'=>'~',
        '0'=>'<',
        '1'=>',',
        '2'=>'.',
        '3'=>':',
        '4'=>';',
        '5'=>'"',
        '6'=>'₢',
        '7'=>'°',
        '8'=>'`',
        '9'=>'´',
    ];
    private $palavra_chave;
    private $senha_gerada;

    function __construct($palavra_chave) {
        $this->palavra_chave = $palavra_chave;
    }

    public function obter_senha()
    {
        
        $this->senha_gerada = $this->remover_dados_pessoais();            
        //RN05-Não conter caracteres contínuos(abc) ou idênticos(aaaa)
        //RN07-Conter letras maiúsculas
        //RN08-Conter letras minúsculas
        //RN09-Conter caracteres especiais
        return $this->senha_gerada;
        
    }
    
    /**
     * Função que remove dados pessoais, utiliza um hash que retorna 64 caracteres
     * Regras de negócio:
     * RN02-Comprimento máximo-64 caraceteres
     * RN03-Comprimento mínimo 8 caracteres
     * RN04-Não conter dados pessoais 
     * RN06-A senha gerada deve ser isenta de grupos exclusivos de caracteres numéricos(1278)e alfabéticos(wsbd)        
     */
    function remover_dados_pessoais(){  
        // "sha1", "sha256", "md5", "haval160, 4"       
        return hash('sha256', $this->palavra_chave . APP_KEY);
    }

    /**
     * 
     */
    function substituir_letras_repetidas(){
        return 'a';
    }
    
    /**
     * 
     */
    function substituir_numeros_repetidos(){
        return 'a';
    }

    function converter_para_caracter_especial($caracter){        
        return $this->tabela_equivalencia[$caracter];
    }
}
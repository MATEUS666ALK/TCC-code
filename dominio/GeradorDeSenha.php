<?php

namespace App\Models;

require_once __DIR__.'/../config.php';
class GeradorDeSenha
{
    private $tabelaDeConversao = [
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
        '7'=>'ç',
        '8'=>'`',
        '9'=>'´',
        //Sempre vai ter um erro a partir da 8/9 execução :Δ;γ;Θ;μ;Ω;Ψ;Ϛ
    ];
    private $palavraChave;
    private $senhaGerada;

    function __construct($palavraChave) {
        $this->palavraChave = $palavraChave;
    }

    public function obter_senha()
    {
        
        $this->senhaGerada = $this->removerDadosPessoais();            
        $this->inserirCaracteresEspeciais();            
        //RN05-Não conter caracteres contínuos(abc) ou idênticos(aaaa)
        //RN07-Conter letras maiúsculas
        //RN09-Conter caracteres especiais
        return $this->senhaGerada;
        
    }
    
    /**
     * Função que remove dados pessoais, utiliza um hash que retorna 64 caracteres
     * Regras de negócio implementadas:
     * RN02-Comprimento máximo-64 caraceteres
     * RN03-Comprimento mínimo 8 caracteres
     * RN04-Não conter dados pessoais 
     * RN06-A senha gerada deve ser isenta de grupos exclusivos de caracteres numéricos(1278)e alfabéticos(wsbd)
     * RN08-Conter letras minúsculas 
     * RN10-Conter caracteres numéricos
     */
    private function removerDadosPessoais(){  
        // "sha1", "sha256", "md5", "haval160, 4"       
        return hash('sha256', $this->palavraChave . APP_KEY);
    }

    /**
     * 
     */
    private function substituirLetrasRepetidas()
    {
        
        return 'a';
    }
    
    /**
     * 
     */
    private function substituirNumerosRepetidos()
    {
        return 'a';
    }

    private function converterParaCaracterEspecial($caracter)
    {   
        return $this->tabelaDeConversao[$caracter];
    }

    /**
     * Função que insere caracteres especiais
     */
    private function inserirCaracteresEspeciais(){
        
    
    }
}
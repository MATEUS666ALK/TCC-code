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
        '5'=>'Ω',
        '6'=>'₢',
        '7'=>'ç',
        '8'=>'`',
        '9'=>'´',
        //Sempre vai ter um erro a partir da 8/9 execução :Δ;γ;Θ;μ;Ω;Ψ;Ϛ
    ];
    private $palavraChave;
    private $listaDeCaracteresDaSenha;    

    function __construct($palavraChave) {
        $this->palavraChave = $palavraChave;
    }

    private function getSenhaGerada()
    {
        return implode('',$this->listaDeCaracteresDaSenha);
    }

    private function setSenhaGerada($senha_parcial)
    {
        $this->listaDeCaracteresDaSenha =  str_split($senha_parcial);
    }
   
    public function obter_senha()

    {
        
        $this->removerDadosPessoais(); 
        $this->substituirLetrasRepetidas();
        $this->substituirNumerosContinuos(); 
        //$this->inserirCaracteresEspeciais(); 
        $this->inserirLetrasMaiusculas();           
        //RN05-Não conter caracteres contínuos(abc) ou idênticos(aaaa)
        //RN07-Conter letras maiúsculas
        //RN09-Conter caracteres especiais
        return $this->getSenhaGerada();
        
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
        $senha_parcial = hash('sha256', $this->palavraChave . APP_KEY);
        $this->setSenhaGerada($senha_parcial);
        return $senha_parcial;
    }
    
    private function substituirLetrasRepetidas(){
        $listaDeCaracteresDaSenha = $this->listaDeCaracteresDaSenha;
        //$ultimo_caracter = ord("z"); chr(104);        
        $ocorrencias_caracter = [];
        $subtituicoes_letras=['z','i','x','j','w','k','y','m','u','n','v','o','r','p','s','q','l','t','ñ','ç','é','ò','õ','ô','ã','à','á','â','ê','è','î','í','ì','ú','û','ù','ü','ï','ö','ä','ë','ÿ','å'];        
        $ponteiro = 0;
        for($indice = 0; $indice < count($listaDeCaracteresDaSenha); $indice++)
        {
            $caracter = $listaDeCaracteresDaSenha[$indice];
            if(array_key_exists($caracter, $ocorrencias_caracter))
            {
                if($caracter >= 'a' and $caracter <= 'z'){
                    if($ponteiro<count($subtituicoes_letras))
                    {
                        $novo_caracter = $subtituicoes_letras[$ponteiro];
                        $this->listaDeCaracteresDaSenha[$indice] = $novo_caracter;
                        $ponteiro++;
                        $ocorrencias_caracter[$novo_caracter] = 1;
                    }else{
                        $ocorrencias_caracter[$caracter]++;
                    }
                }else{
                    $ocorrencias_caracter[$caracter]++;
                }                
            }else{
                $ocorrencias_caracter[$caracter] = 1;
            }            
        }
    }

    private function substituirNumerosContinuos($quantidade_maxima=2){
        $listaDeCaracteresDaSenha = $this->listaDeCaracteresDaSenha;              
        $subtituicoes=['¹','²','³','°','¾','ø','£','×','Ø','ƒ','¢','ª','º','¿','½','¼','¡','«','»','¦','¥','¤','ð','Ð','ß','µ','Þ','Þ','±','§','¶','æ','Æ'];        
        $indice_substituicao = 0;
        $quantidade_continuos = 0;
        for($indice = 0; $indice < count($listaDeCaracteresDaSenha); $indice++)
        {
            $caracter = $listaDeCaracteresDaSenha[$indice];

            if($caracter >= '0' and $caracter <= '9'){
                $quantidade_continuos++;
                if($quantidade_continuos>$quantidade_maxima)
                {
                    if($indice_substituicao<count($subtituicoes))
                    {
                        $novo_caracter = $subtituicoes[$indice_substituicao];
                        $this->listaDeCaracteresDaSenha[$indice] = $novo_caracter;
                        $indice_substituicao++;                        
                    }
                    $quantidade_continuos = 0;
                }
            }else{
                $quantidade_continuos = 0;
            }            
        }
    }

    private function converterParaCaracterEspecial($caracter)
    {   
        if(array_key_exists($caracter,$this->tabelaDeConversao)){
            return $this->tabelaDeConversao[$caracter];
        }
        return $caracter;
    }

    /**
     * Função que insere caracteres especiais
     */
    private function inserirCaracteresEspeciais(){
        
        $senha_gerada = $this->listaDeCaracteresDaSenha;
        
        for($indice = 0; $indice < count($senha_gerada); $indice++)
        {
            $letra = $this->listaDeCaracteresDaSenha[$indice];
      //        if( $alguma_logica){
             $this->listaDeCaracteresDaSenha[$indice] = $this->converterParaCaracterEspecial($letra);
      //      }      
         
        }
    }

    private function inserirLetrasMaiusculas(){
        
        $senha_gerada = $this->listaDeCaracteresDaSenha;
        
        for($indice = 0; $indice < count($senha_gerada); $indice++)
        {
            $letra = $this->listaDeCaracteresDaSenha[$indice];
      //        if( $alguma_logica){
             $this->listaDeCaracteresDaSenha[$indice] = strtoupper($letra);
      //      }      
         
        }
    }
}
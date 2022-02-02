<?php

namespace App\Models;

require_once __DIR__.'/../config.php';
class GeradorDeSenha
{
    private $tabelaConversao = [
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

    function __construct($palavraChave) 
    {
        $this->palavraChave = $palavraChave;
    }

    private function getSenhaGerada()
    {
        return implode('',$this->listaDeCaracteresDaSenha); //retorna os elementos como se fossem um vetor
    }

    private function setSenhaGerada($SenhaParcial)
    {
        $this->listaDeCaracteresDaSenha =  str_split($SenhaParcial); //Converte uma string para um array
    }
   
    public function obter_senha() //função mãe, a qual é chamada, quando utilizamos a classe
    {
        
        $this->removerDadosPessoais(); 
        $this->substituirLetrasRepetidas();
        $this->substituirNumerosContinuos(); 
        $this->inserirCaracteresEspeciais(); 
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
    private function removerDadosPessoais() //função remover dados/acionar a função hash,com 64 caracteres
    // de acordo com a norma RN04-Não conter dados pessoais e RN08-Conter letras minúsculas e RN10-Conter caracteres numéricos
    {  
        // "sha1", "sha256", "md5", "haval160, 4"       
        $SenhaParcial = hash('sha256', $this->palavraChave . APP_KEY);
        $this->setSenhaGerada($SenhaParcial);
        return $SenhaParcial;
    }
    
    private function substituirLetrasRepetidas()
    // de acordo com a norma RN06-A senha gerada deve ser isenta de grupos exclusivos de caracteres numéricos(1278)e alfabéticos(wsbd) 
    {
        $listaDeCaracteresDaSenha = $this->listaDeCaracteresDaSenha; //chamada da variavel
        //$ultimo_caracter = ord("z"); chr(104);        
        $ocorrencias_caracter = [];
        $subtituicoes_letras=['z','i','x','j','w','k','y','m','u','n','v','o','s','p','r','q','l','t','ñ','ç','é','ò','õ','ô','ã','à','á','â','ê','è','î','í','ì','ú','û','ù','ü','ï','ö','ä','ë','ÿ','å'];        
        $ponteiro = 0;
        for($contador = 0; $contador < count($listaDeCaracteresDaSenha); $contador++)
        {
            $caracter = $listaDeCaracteresDaSenha[$contador];
            if(array_key_exists($caracter, $ocorrencias_caracter))//??
            {
                if($caracter >= 'a' and $caracter <= 'z'){//&&
                    if($ponteiro<count($subtituicoes_letras))
                    {
                        $novo_caracter = $subtituicoes_letras[$ponteiro];
                        $this->listaDeCaracteresDaSenha[$contador] = $novo_caracter;
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

    private function substituirNumerosContinuos($quantidade_maxima=2)
    {
    // de acordo com a norma RN06-A senha gerada deve ser isenta de grupos exclusivos de caracteres numéricos(1278)e alfabéticos(wsbd)    
        $listaDeCaracteresDaSenha = $this->listaDeCaracteresDaSenha;              
        $subtituicoes=['ø','£','×','Ø','ƒ','¢','¡','«','»','¦','¥','¤','ð','Ð','ß','µ','Þ','Þ','±','§','¶','æ','Æ'];   //caracteres da tabela ASCII     
        $contador_substituicao = 0;
        $quantidade_continuos = 0;
        for($contador = 0; $contador < count($listaDeCaracteresDaSenha); $contador++)
        {
            $caracter = $listaDeCaracteresDaSenha[$contador];

            if($caracter >= '0' and $caracter <= '9'){
                $quantidade_continuos++;
                if($quantidade_continuos>$quantidade_maxima)
                {
                    if($contador_substituicao<count($subtituicoes))//Conta o número de elementos de uma variável, ou propriedades de um objeto
                    {
                        $novo_caracter = $subtituicoes[$contador_substituicao];
                        $this->listaDeCaracteresDaSenha[$contador] = $novo_caracter;
                        $contador_substituicao++;                        
                    }
                    $quantidade_continuos = 0;
                }
            }else
            {
                $quantidade_continuos = 0;
            }            
        }
    }

    private function converterParaCaracterEspecial($caracter)
    {   
        if(array_key_exists($caracter,$this->tabelaConversao)) // caso exista retorne a tabela
        {
            return $this->tabelaConversao[$caracter];
        }
        return $caracter;
    }

    /**
     * Função que insere caracteres especiais
     */
    private function inserirCaracteresEspeciais()
    {   
        
        $caracteresEspeciais = [0,2,4,12,25,35,38,42,54,62];
        for($indice = 0; $indice < count($this->listaDeCaracteresDaSenha); $indice++)
        {
            
            $letra = $this->listaDeCaracteresDaSenha[$indice];
            if(in_array($indice,$caracteresEspeciais)){
             $this->listaDeCaracteresDaSenha[$indice] = $this->converterParaCaracterEspecial($letra);
            }      
         
        }
    }

    private function inserirLetrasMaiusculas()
    // de acordo com a norma RN07-Conter letras maiúsculas
    {   
        $maiuscula = [1,3,5,8,12,19,25,31,46,52,63];
        $contador = 0;
        for($indice = 0; $indice < count($this->listaDeCaracteresDaSenha); $indice++)
        {
            $letra = $this->listaDeCaracteresDaSenha[$indice];
            if( $letra>= 'a' && $letra<='z'){
               $contador++;
               if(in_array($contador,$maiuscula)){
                $this->listaDeCaracteresDaSenha[$contador] = strtoupper($letra);
               }
            }      
        }
    }
    //Use somente letras (a-z e A-Z), números (0-9) e caracteres especiais, como !@#$%^&*.() gogle não aceita todos os caracteres
}
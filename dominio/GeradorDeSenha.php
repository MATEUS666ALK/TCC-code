<?php
namespace App\Models;

require_once __DIR__ . '/../config.php';
class GeradorDeSenha
{
    private $tabelaConversao = [
        'a' => '%',
        'b' => '$',
        'c' => '@',
        'd' => '&',
        'e' => '!',
        'f' => '*',
        'g' => '¢',
        'h' => '|',
        'i' => '-',
        'j' => '+',
        'k' => '=',
        'l' => '/',
        'm' => '_',
        'n' => '?',
        'o' => '#',
        'p' => '{',
        'q' => '}',
        'r' => '[',
        's' => ']',
        't' => '¬',
        'u' => '¨',
        'v' => ')',
        'w' => '(',
        'x' => '>',
        'y' => '^',
        'z' => '~',
        '0' => '<',
        '1' => ',',
        '2' => '.',
        '3' => ':',
        '4' => ';',
        '5' => 'Ω',
        '6' => '₢',
        '7' => 'ç',
        '8' => '`',
        '9' => '´',
        //Sempre vai ter um erro a partir da 8/9 execução :Δ;γ;Θ;μ;Ω;Ψ;Ϛ
    ];
    private $subtituicoesNumeros = ['ø', '£', '×', 'Ø', 'ƒ', '¢', '¡', '«', '»', '¦', '¥', '¤', 'ð', 'Ð', 'ß', 'µ', 'Þ', 'Þ', '±', '§', '¶', 'æ', 'Æ'];
    private $subtituicoesLetras = ['z', 'i', 'x', 'j', 'w', 'k', 'y', 'm', 'u', 'n', 'v', 'o', 's', 'p', 'r', 'q', 'l', 't', 'ñ', 'ç', 'é', 'ò', 'õ', 'ô', 'ã', 'à', 'á', 'â', 'ê', 'è', 'î', 'í', 'ì', 'ú', 'û', 'ù', 'ü', 'ï', 'ö', 'ä', 'ë', 'ÿ', 'å'];
    private $palavraChave;
    private $listaDeCaracteresDaSenha;

    function __construct($palavraChave)
    {
        $this->palavraChave = $palavraChave;
    }

    private function getSenhaGerada()
    {
        return implode('', $this->listaDeCaracteresDaSenha); //retorna os elementos como se fossem um vetor
    }

    private function setSenhaGerada($SenhaParcial)
    {
        $this->listaDeCaracteresDaSenha =  str_split($SenhaParcial); //Converte uma string para um array
    }

    public function obterSenha($padraoGoogleCaracteres = true) //função mãe, a qual é chamada, quando utilizamos a classe
    {
        if($padraoGoogleCaracteres){
            $this->popularTabelaConversaoPadraoGoogle();
        }
        $this->removerDadosPessoais();
        $this->inserirCaracteresEspeciais();
        $this->substituirLetrasRepetidas();
        $this->substituirNumerosContinuos();
        $this->inserirLetrasMaiusculas();

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
    private function removerDadosPessoais()
    {
        // "sha1", "sha256", "md5", "haval160, 4"       
        $SenhaParcial = hash('sha256', $this->palavraChave . APP_KEY);
        $this->setSenhaGerada($SenhaParcial);
        return $SenhaParcial;
    }

    /**
     * Função que susbistitui letras repetidas, utiliza um array xom caracteres a serem substituidos
     * Regras de negócio implementadas:
     * RN06-A senha gerada deve ser isenta de grupos exclusivos de caracteres numéricos(1278)e alfabéticos(wsbd)
     */
    private function substituirLetrasRepetidas()
    {
        $listaDeCaracteresDaSenha = $this->listaDeCaracteresDaSenha; //chamada da variavel
        //$ultimo_caracter = ord("z"); chr(104);        
        $ocorrencias_caracter = [];
        $subtituicoes_letras = $this->subtituicoesLetras;
        $ponteiro = 0;
        for ($contador = 0; $contador < count($listaDeCaracteresDaSenha); $contador++) {
            $caracter = $listaDeCaracteresDaSenha[$contador];
            if (array_key_exists($caracter, $ocorrencias_caracter)) //??
            {
                if ($caracter >= 'a' and $caracter <= 'z') { //&&
                    if ($ponteiro < count($subtituicoes_letras)) {
                        $novo_caracter = $subtituicoes_letras[$ponteiro];
                        $this->listaDeCaracteresDaSenha[$contador] = $novo_caracter;
                        $ponteiro++;
                        $ocorrencias_caracter[$novo_caracter] = 1;
                    } else {
                        $ocorrencias_caracter[$caracter]++;
                    }
                } else {
                    $ocorrencias_caracter[$caracter]++;
                }
            } else {
                $ocorrencias_caracter[$caracter] = 1;
            }
        }
    }

    /**
     * Função que susbistitui numeros continuos, utiliza um array com caracteres a serem substituidos
     * Regras de negócio implementadas:
     * RN06-A senha gerada deve ser isenta de grupos exclusivos de caracteres numéricos(1278)e alfabéticos(wsbd)
     */
    private function substituirNumerosContinuos($quantidade_maxima = 2)
    {

        $subtituicoes = $this->subtituicoesNumeros;
        $contador_substituicao = 0;
        $quantidade_continuos = 0;
        for ($indice = 0; $indice < count($this->listaDeCaracteresDaSenha); $indice++) {
            $caracter = $this->listaDeCaracteresDaSenha[$indice];

            if ($caracter >= '0' and $caracter <= '9') {
                $quantidade_continuos++;
                if ($quantidade_continuos > $quantidade_maxima) {
                    if ($contador_substituicao < count($subtituicoes)) //Conta o número de elementos de uma variável, ou propriedades de um objeto
                    {
                        $novo_caracter = $subtituicoes[$contador_substituicao];
                        $this->listaDeCaracteresDaSenha[$indice] = $novo_caracter;
                        $contador_substituicao++;
                    }
                    $quantidade_continuos = 0;
                }
            } else {
                $quantidade_continuos = 0;
            }
        }
    }

    /**
     * Função que substitui o caracter passado por um caracter especial da tabela de conversão.
     */
    private function converterParaCaracterEspecial($caracter)
    {
        if (array_key_exists($caracter, $this->tabelaConversao)) // caso exista retorne a tabela
        {
            return $this->tabelaConversao[$caracter];
        }
        return $caracter;
    }

    /**
     * Função que inseri caracter especial de acordo ocorrencia no array.
     * Regras de negócio implementadas:
     * RN09-Conter caracteres especiais
     */
    private function inserirCaracteresEspeciais()
    {
        $contador = 0;
        $ocorrencias = [0, 2, 4, 12, 25, 35, 38, 42, 54, 62];
        for ($indice = 0; $indice < count($this->listaDeCaracteresDaSenha); $indice++) {
            $letra = $this->listaDeCaracteresDaSenha[$indice];
            if ($letra >= '0' and $letra <= '9') {
                if (in_array($contador, $ocorrencias)) {
                    $this->listaDeCaracteresDaSenha[$indice] = $this->converterParaCaracterEspecial($letra);
                }
                $contador++;
            }
        }
    }

    /**
     * Função que inseri letras maiusculas com base no array de ocorrencias
     * Regras de negócio implementadas:
     * RN07-Conter letras maiúsculas
     */
    private function inserirLetrasMaiusculas()
    {
        $ocorrencias = [1, 3, 5, 8, 12, 19, 25, 31, 46, 52, 63];
        $contador = 0;
        for ($indice = 0; $indice < count($this->listaDeCaracteresDaSenha); $indice++) {
            $letra = $this->listaDeCaracteresDaSenha[$indice];
            if ($letra >= 'a' && $letra <= 'z') {
                $contador++;
                if (in_array($contador, $ocorrencias)) {
                    $this->listaDeCaracteresDaSenha[$contador] = strtoupper($letra);
                }
            }
        }
    }
    private function popularTabelaConversaoPadraoGoogle(){
//Use somente letras (a-z e A-Z), números (0-9) e caracteres especiais, como !@#$%^ *.() gogle não aceita todos os caracteres
//hotmal não pode letras gregas, mais e menos, ç,simbolo de moedas
         $this->tabelaConversao = [
            'a' => '!', 
            'b' => '$',
            'c' => '@',
            'd' => '&',
            'e' => '!',
            'f' => '*',
            'g' => '^',
            'h' => '|',
            'i' => '-',
            'j' => '+',
            'k' => '=',
            'l' => '/',
            'm' => '_',
            'n' => '?',
            'o' => '#',
            'p' => '{',
            'q' => '}',
            'r' => '[',
            's' => ']',
            't' => '',
            'u' => '¨',
            'v' => ')',
            'w' => '(',
            'x' => '>',
            'y' => '^',
            'z' => '~',
            '0' => '<',
            '1' => ',',
            '2' => '.',
            '3' => ':',
            '4' => ';',
            '5' => '!',
            '6' => '@',
            '7' => '&',
            '8' => '#',
            '9' => '$',
            //Sempre vai ter um erro a partir da 8/9 execução :Δ;γ;Θ;μ;Ω;Ψ;Ϛ
        ];
         $this->subtituicoesNumeros = ['ø', '£', '×', 'Ø', 'ƒ', '¢', '¡', '«', '»', '¦', '¥', '¤', 'ð', 'Ð', 'ß', 'µ', 'Þ', 'Þ', '±', '§', '¶', 'æ', 'Æ'];
         $this->subtituicoesLetras = ['z', 'i', 'x', 'j', 'w', 'k', 'y', 'm', 'u', 'n', 'v', 'o', 's', 'p', 'r', 'q', 'l', 't','A','M'];
    }
}

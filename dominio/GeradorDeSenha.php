<?php
function obter_senha( $palavra_chave ){
//RN03 não pode ter relação com nomes, números de telefones, datas de nascimento
    $senha_gerada = hash('sha256', $palavra_chave);// "sha1", "sha256", "md5", "haval160, 4" 
// TODO:logica do algoritmo
//A senha gerada não pode ser maior que 64 caracteres-RN02; minimo de 8 caracteres-RN08
//caracteres contínuos(abc) idênticos(aaaa) ou grupos totalmente numéricos(1278) ou totalmente alfabéticos(wsbd).
$letras = str_split($senha_gerada);
//percorre cada uma das letras da senha gerada
foreach($letras as $letra){

};
    return $senha_gerada;
    
}
function converter_para_caracter_especial($caracter){
    $tabela_equivalencia = [
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
return $tabela_equivalencia[$caracter];
}
   
//strtolower()  – Converte uma string para minúsculas;
//strtoupper()  – Converte uma string para maiúsculas;
//ucfirst() – Converte para maiúscula o primeiro caractere de uma string;
//ucwords() -Converte para maiúsculas o primeiro caractere de cada palavra;
//intval();
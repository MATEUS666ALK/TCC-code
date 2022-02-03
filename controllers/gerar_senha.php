<?php
use App\Models\GeradorDeSenha;
session_start( );
require_once __DIR__.'/../dominio/GeradorDeSenha.php';
$palavra_chave = filter_input(INPUT_POST,'palavra-chave',FILTER_SANITIZE_STRING);
//var_dump($_POST);
//die();
$quantidade_caracteres = filter_input(INPUT_POST,'quantidade_caracteres',FILTER_SANITIZE_NUMBER_INT);

if($quantidade_caracteres < 8 ){
    $quantidade_caracteres = 8;
}elseif($quantidade_caracteres>64){
    $quantidade_caracteres = 64;
}

$gerador = new GeradorDeSenha($palavra_chave);
$senha_gerada = $gerador->obterSenha();
$_SESSION['quantidade_caracteres'] = $quantidade_caracteres;
$_SESSION['senha_gerada'] = substr($senha_gerada,0,$quantidade_caracteres);
$_SESSION['palavra_chave'] = $palavra_chave;
header('location: /');
die();
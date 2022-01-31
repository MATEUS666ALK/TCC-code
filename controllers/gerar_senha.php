<?php
use App\Models\GeradorDeSenha;
session_start( );
require_once __DIR__.'/../dominio/GeradorDeSenha.php';
$palavra_chave = filter_input(INPUT_POST,'palavra-chave',FILTER_SANITIZE_STRING);
$gerador = new GeradorDeSenha($palavra_chave);
$_SESSION['senha_gerada'] = $gerador->obter_senha();
$_SESSION['palavra_chave'] = $palavra_chave;
header('location: /');
die();



<?php
session_start( );
require_once __DIR__.'/../dominio/GeradorDeSenha.php';
$palavra_chave = filter_input(INPUT_POST,'palavra-chave',FILTER_SANITIZE_STRING);
$_SESSION['senha_gerada'] = obter_senha($palavra_chave);
$_SESSION['palavra_chave'] = $palavra_chave;
header('location: /');
die();



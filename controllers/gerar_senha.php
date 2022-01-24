<?php
session_start( );
$palavra_chave = filter_input(INPUT_POST,'palavra-chave',FILTER_SANITIZE_STRING);
$senha_gerada = md5($palavra_chave);
// TODO:logica do algoritmo
$_SESSION['senha_gerada'] = $senha_gerada;
$_SESSION['palavra_chave'] = $palavra_chave;
header('location: /');



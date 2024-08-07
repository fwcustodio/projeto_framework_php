<?
//Starta/Limpa/Destroi/redireciona
session_start();

session_unset();

session_destroy();

header("Location: ../");
?>
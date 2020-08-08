<?php

function conectar(){
    $host = 'localhost'; //host (acho que o padrão é localhost, deixei)
    $user = ''; //user do sql
    $senha = ''; //senha do sql
    $database = ''; //nome da db

    $conn = new mysqli($host, $user, $senha, $database) or 
    die("Conexão falhou: %s\n". $conn -> error);

    return $conn;
}

function fechar($conn){
    $conn -> close();
}
?>
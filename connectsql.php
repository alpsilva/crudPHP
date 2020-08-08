<?php

function conectar(){
    $host = 'localhost';
    $user = 'alps2';
    $senha = 'SQL987pass';
    $database = 'database';

    $conn = new mysqli($host, $user, $senha, $database) or 
    die("Conexão falhou: %s\n". $conn -> error);

    return $conn;
}

function fechar($conn){
    $conn -> close();
}
?>
<?php

$servidor = "localhost:3307";
$usuario = "root";
$senha = "";
$nome_banco = "leshar_oficial";

$conexao = new mysqli($servidor, $usuario, $senha, $nome_banco);

if ($conexao->connect_error){
    echo $conexao->connect_error;
}
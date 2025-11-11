<?php
include_once("conexao.php"); // todos os arquivos que precisam de conexao com o banco de dados
session_start(); 


// inicialização do array de retorno
$retorno = [
    "status"=> "",
    "mensagem"=> "",
    "data"=> []
];

// Atribuição
$hora_inicio = $_POST['hora_inicio'];
$hora_fim = $_POST['hora_fim'];
$mensagem = $_POST['mensagem'];

$stmt = $conexao->prepare("INSERT INTO aula (hora_inicio, hora_fim,mensagem)
 VALUES (?,?,?)");
$stmt->bind_param("sss",$hora_inicio,$hora_fim,$mensagem); // s = string, i = inteiro
$stmt->execute(); // executa a query

 if($stmt->affected_rows > 0){
        $retorno = [
            "status"=> "ok",
            "mensagem"=> $stmt->affected_rows." registros inseridos com sucesso",
            "data"=> []
        ];
 }else{
        $retorno = [
            "status"=> "erro",
            "mensagem"=> "0 registros inseridos",
            "data"=> []
        ];
    }

$stmt->close();

 // fecha a conexao com o banco de dados
$conexao->close();
header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno); // converte o array de retorno em json e exibe na tela


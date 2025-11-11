<?php
include_once("conexao.php"); // todos os arquivos que precisam de conexao com o banco de dados
session_start(); 

$retorno = [
    "status"=> "",
    "mensagem"=> "",
    "data"=> []
];

if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Atribuição
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];
    $mensagem = $_POST['mensagem'];

    //preparar a query via statement para enviar ao banco de dados
    $stmt = $conexao->prepare("UPDATE aula SET hora_inicio = ?, hora_fim = ?, mensagem = ? WHERE id = ?");
    $stmt->bind_param("sssi",$hora_inicio,$hora_fim,$mensagem, $id); // s = string, i = inteiro
    $stmt->execute(); // executa a query

    if($stmt->affected_rows > 0){
            $retorno = [
                "status"=> "ok",
                "mensagem"=> $stmt->affected_rows."registros inseridos com sucesso",
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
} else {
    // inicialização do array de retorno
    $retorno = [
        "status"=> "erro",
        "mensagem"=> "necessário informar o id para alteração",
        "data"=> []
    ];
}

 // fecha a conexao com o banco de dados
$conexao->close();
header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno); // converte o array de retorno em json e exibe
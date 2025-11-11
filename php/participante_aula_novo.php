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

$resultado = $stmt->prepare("SELECT id FROM aluno WHERE email = ?")

$aluno_id = $_POST['email_aluno'];
$aula_id = $_POST['aula_id'];
$mensagem = $_POST['mensagem'];

$mentor_id = $_SESSION['user_id'];


$stmt = $conexao->prepare("INSERT INTO aula (email_aluno, hora_fim,mensagem)
 VALUES (?,?,?)");
$stmt->bind_param("sss",$email_aluno,$hora_fim,$mensagem); // s = string, i = inteiro
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


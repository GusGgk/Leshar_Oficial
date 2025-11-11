<?php
include_once("conexao.php");
session_start(); 

$retorno = [
    "status"=> "erro",
    "mensagem"=> "Erro desconhecido",
    "data"=> []
];

$pontuacao = $_POST['pontuacao'] ?? 0;
$data = $_POST['data'] ?? ''; 
$mensagem = $_POST['mensagem'] ?? '';
$participante_aula_id = (int)($_POST['participante_aula_id'] ?? 0);

if ($participante_aula_id <= 0) {
    $retorno["mensagem"] = "ID do Participante da Aula é obrigatório.";
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($retorno);
    exit;
}
if (empty($data)) {
    $data = date('Y-m-d'); //data atual se não for enviada
}

$stmt = $conexao->prepare("INSERT INTO avaliacao (pontuacao, data, mensagem, participante_aula_id)
 VALUES (?,?,?,?)");
$stmt->bind_param("dssi",$pontuacao,$data,$mensagem, $participante_aula_id); 
$stmt->execute();

 if($stmt->affected_rows > 0){
        $retorno = [
            "status"=> "ok",
            "mensagem"=> $stmt->affected_rows." registros inseridos com sucesso",
            "data"=> []
        ];
 }else{
        $retorno["mensagem"] = "0 registros inseridos. Erro: " . $stmt->error;
    }

$stmt->close();
$conexao->close();
header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
?>
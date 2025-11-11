<?php
include_once("conexao.php");
session_start(); 

$retorno = [
    "status"=> "erro",
    "mensagem"=> "",
    "data"=> []
];

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];

    $pontuacao = $_POST['pontuacao'] ?? 0;
    $data = $_POST['data'] ?? '';
    $mensagem = $_POST['mensagem'] ?? '';

    if (empty($data)) {
        $retorno["mensagem"] = "O campo Data é obrigatório.";
        echo json_encode($retorno);
        exit;
    }
    $stmt = $conexao->prepare("UPDATE avaliacao SET pontuacao = ?, data = ?, mensagem = ? WHERE id = ?");
    $stmt->bind_param("dssi",$pontuacao, $data, $mensagem, $id); // d, s, s, i
    $stmt->execute(); 

    if($stmt->affected_rows > 0){
            $retorno = [
                "status"=> "ok",
                "mensagem"=> $stmt->affected_rows." registro alterado com sucesso",
                "data"=> []
            ];
    }else{
            $retorno["mensagem"] = "0 registros alterados (ou dados iguais). Erro: " . $stmt->error;
        }
    $stmt->close();
} else {
    $retorno["mensagem"] = "Necessário informar o id para alteração";
}

$conexao->close();
header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
?>
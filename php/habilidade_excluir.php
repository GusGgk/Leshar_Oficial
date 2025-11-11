<?php
include_once("conexao.php");

$retorno = [
    "status"=> "erro",
    "mensagem"=> "necessário informar o id para exclusão",
    "data"=> []
];

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $conexao->prepare("DELETE FROM habilidade WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if($stmt->affected_rows > 0){
        $retorno = [
            "status"=> "ok",
            "mensagem"=> $stmt->affected_rows." habilidade excluída com sucesso",
            "data"=> []
        ];
    }else{
        $retorno["mensagem"] = "Nenhum registro foi modificado";
    }
    $stmt->close();
}

$conexao->close();

header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
?>
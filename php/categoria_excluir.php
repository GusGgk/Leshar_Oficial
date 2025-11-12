<?php
include_once("conexao.php");
session_start(); 

$retorno = [
    "status"=> "erro",
    "mensagem"=> "",
    "data"=> []
];

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'ADM'){
    $retorno["mensagem"] = "Acesso negado. Requer privilégios de Administrador.";
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($retorno);
    exit;
}

if(isset($_GET['id'])){ 
    $id = (int)$_GET['id'];     
    $stmt = $conexao->prepare("DELETE FROM categoria_habilidade WHERE id = ?"); 
    $stmt->bind_param("i", $id); 
    $stmt->execute(); 

    if($stmt->affected_rows > 0){
        $retorno = [
            "status"=> "ok",
            "mensagem"=> "Categoria excluída com sucesso.",
            "data"=> []
        ];
    }else{
        $retorno["mensagem"] = "Nenhum registro foi modificado (ID não encontrado).";
    }
    $stmt->close();
} else {
    $retorno["mensagem"] = "Necessário informar o ID para exclusão.";
}
$conexao->close();

header('Content-Type: application/json;charset=utf-8'); 
echo json_encode($retorno);
?>
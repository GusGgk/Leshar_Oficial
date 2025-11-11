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
    $nome = $_POST['nome'] ?? '';

    if(strlen(trim($nome)) > 0){
        $stmt = $conexao->prepare("UPDATE categoria_habilidade SET nome = ? WHERE id = ?");
        $stmt->bind_param("si", $nome, $id);
        $stmt->execute();

        if($stmt->affected_rows > 0){
                $retorno = [
                    "status"=> "ok",
                    "mensagem"=> $stmt->affected_rows." registro alterado com sucesso",
                    "data"=> []
                ];
        }else{
                $retorno["mensagem"] = "Nenhuma alteração aplicada (ou nome já existente).";
        }
        $stmt->close();
    } else {
        $retorno["mensagem"] = "O nome não pode ficar em branco.";
    }
    
} else {
    $retorno["mensagem"] = "Necessário informar o ID para alteração.";
}

$conexao->close();
header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
?>
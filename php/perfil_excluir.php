<?php
include_once("conexao.php");
session_start();

$retorno = [
    "status"=> "erro",
    "mensagem"=> "",
    "data"=> []
];

if(!isset($_SESSION['usuario'])){
    $retorno['mensagem'] = 'Não autenticado';
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($retorno);
    exit;
}

$isAdmin = isset($_SESSION['usuario']['tipo_usuario']) && $_SESSION['usuario']['tipo_usuario'] === 'ADM';
$sessionUserId = (int)($_SESSION['usuario']['id'] ?? 0);

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    if(!$isAdmin && $id !== $sessionUserId){
        $retorno['mensagem'] = 'Sem permissão para excluir este usuário';
    } else {
        $stmt = $conexao->prepare("DELETE FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $retorno = [
                "status"=> "ok",
                "mensagem"=> 'Usuário excluído',
                "data"=> []
            ];
            // Se excluiu a si mesmo, encerra sessão
            if($id === $sessionUserId){
                session_destroy();
            }
        } else {
            $retorno['mensagem'] = 'Nenhum registro foi modificado';
        }
        $stmt->close();
    }
} else {
    $retorno['mensagem'] = 'necessário informar o id para exclusão';
}

$conexao->close();

header('Content-Type: application/json;charset=utf-8'); 
echo json_encode($retorno);

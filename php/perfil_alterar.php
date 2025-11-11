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
        $retorno['mensagem'] = 'Sem permissão para alterar este perfil';
    } else {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $bio = $_POST['bio'] ?? '';
        $localizacao = $_POST['localizacao'] ?? '';
        if(strlen($nome)===0 || strlen($email)===0){
            $retorno['mensagem'] = 'Nome e email são obrigatórios';
        } else {
            $stmt = $conexao->prepare("UPDATE usuario SET nome = ?, email = ?, bio = ?, localizacao = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $nome, $email, $bio, $localizacao, $id);
            $stmt->execute();
            if($stmt->affected_rows > 0){
                $retorno = [
                    "status"=> "ok",
                    "mensagem"=> 'Registro alterado com sucesso',
                    "data"=> []
                ];
            } else {
                $retorno['mensagem'] = 'Nenhuma alteração aplicada';
            }
            $stmt->close();
        }
    }
} else {
    $retorno['mensagem'] = 'necessário informar o id para alteração';
}

$conexao->close();
header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
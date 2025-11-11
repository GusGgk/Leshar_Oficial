<?php
include_once("conexao.php");
session_start();

// inicialização do array de retorno
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
    if (!$isAdmin && $id !== $sessionUserId) {
        $retorno['mensagem'] = 'Sem permissão para visualizar este perfil';
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($retorno);
        exit;
    }
    $stmt = $conexao->prepare("SELECT id, nome, email, bio, localizacao, data_cadastro FROM usuario WHERE id = ?");
    $stmt->bind_param("i", $id);
} else {
    if ($isAdmin) {
        $stmt = $conexao->prepare("SELECT id, nome, email, bio, localizacao, data_cadastro FROM usuario");
    } else {
        // Usuário comum: retorna somente o próprio registro
        $stmt = $conexao->prepare("SELECT id, nome, email, bio, localizacao, data_cadastro FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $sessionUserId);
    }
}

$stmt->execute();
$resultado = $stmt->get_result();

$tabela = [];
if($resultado->num_rows > 0){
    while($linha = $resultado->fetch_assoc()){
        $tabela[] = $linha;
    }
    $retorno = [
        "status" => "ok",
        "mensagem" => "registros encontrados com sucesso!",
        "data" => $tabela
    ];
} else {
    $retorno = [
        "status" => "erro",
        "mensagem" => "Não encontrou registros",
        "data" => []
    ];
}

$stmt->close();
$conexao->close();

header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
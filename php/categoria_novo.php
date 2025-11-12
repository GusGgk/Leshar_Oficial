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

if (isset($_POST['nome']) && strlen(trim($_POST['nome'])) > 0) {
    
    $nome = trim($_POST['nome']);

    $checkStmt = $conexao->prepare("SELECT id FROM categoria_habilidade WHERE nome = ?");
    $checkStmt->bind_param("s", $nome);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        $retorno["mensagem"] = "Erro: Esta categoria já existe.";
    } else {
        $stmt = $conexao->prepare("INSERT INTO categoria_habilidade (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);
        $stmt->execute();

        if($stmt->affected_rows > 0){
                $retorno = [
                    "status"=> "ok",
                    "mensagem"=> $stmt->affected_rows." categoria inserida com sucesso",
                    "data"=> []
                ];
        } else {
                $retorno["mensagem"] = "Erro ao inserir no banco de dados.";
        }
        $stmt->close();
    }
    $checkStmt->close();
} else {
    $retorno["mensagem"] = "O nome da categoria não pode estar vazio.";
}

$conexao->close();
header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
?>
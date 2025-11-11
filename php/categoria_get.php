<?php
include_once("conexao.php");
session_start(); 

$retorno = [
    "status"=> "erro",
    "mensagem"=> "",
    "data"=> []
];

// Validação de ADM 
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'ADM'){
    $retorno["status"] = "erro";
    $retorno["mensagem"] = "Acesso negado. Requer privilégios de Administrador.";
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($retorno);
    exit;
}

// Lógica de busca 
$stmt = $conexao->prepare("SELECT * FROM categoria_habilidade ORDER BY nome ASC");
$stmt->execute(); 
$resultado = $stmt->get_result(); 

$tabela = []; 
if($resultado->num_rows > 0){
    while($linha = $resultado-> fetch_assoc()){
        $tabela[] = $linha;
    }
    $retorno = [
        "status" => "ok",
        "mensagem" => "registros encontrados com sucesso!",
        "data" => $tabela
    ];
} else {
    $retorno = [
        "status" => "ok", 
        "mensagem" => "Nenhuma categoria cadastrada ainda.",
        "data" => []
    ];
}

$stmt->close();
$conexao->close();

header('Content-Type: application/json;charset=utf-8'); 
echo json_encode($retorno);
?>
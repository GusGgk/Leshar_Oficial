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
    $id = $_GET['id'];
    $stmt = $conexao->prepare("SELECT * FROM categoria_habilidade WHERE id = ?");
    $stmt->bind_param("i", $id);
} else {
    $stmt = $conexao->prepare("SELECT * FROM categoria_habilidade ORDER BY nome ASC");
}

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
        "mensagem" => "Não encontrou registros",
        "data" => [] // data vazia
    ];
}

$stmt->close();
$conexao->close();

header('Content-Type: application/json;charset=utf-8'); 
echo json_encode($retorno);
?>
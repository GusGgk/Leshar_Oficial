<?php
include_once("conexao.php");
$retorno = [
    "status"=> "erro",
    "mensagem"=> "0 registros inseridos",
    "data"=> []
];

$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$categoria_id = (int)($_POST['categoria_id'] ?? 0);

if (!empty($nome) && !empty($descricao) && $categoria_id > 0) {
    
    //insere na tabela habilidade
    $stmt = $conexao->prepare("INSERT INTO habilidade (nome, descricao, categoria_id)
                             VALUES (?,?,?)");
    $stmt->bind_param("ssi", $nome, $descricao, $categoria_id);
    $stmt->execute();

    if($stmt->affected_rows > 0){
            $retorno = [
                "status"=> "ok",
                "mensagem"=> $stmt->affected_rows." habilidade inserida com sucesso",
                "data"=> []
            ];
    }else{
            $retorno["mensagem"] = "Erro ao inserir no banco: " . $stmt->error;
    }
    $stmt->close();

} else {
    $retorno["mensagem"] = "Dados incompletos (Nome, Descrição e Categoria são obrigatórios).";
}

$conexao->close();
header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
?>
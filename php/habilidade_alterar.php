<?php
include_once("conexao.php");

$retorno = [
    "status"=> "erro",
    "mensagem"=> "necessário informar o id para alteração",
    "data"=> []
];

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $categoria_id = (int)($_POST['categoria_id'] ?? 0);

    if (!empty($nome) && !empty($descricao) && $categoria_id > 0) {
        
        $stmt = $conexao->prepare("UPDATE habilidade SET nome = ?, descricao = ?, categoria_id = ? WHERE id = ?");
        $stmt->bind_param("ssii", $nome, $descricao, $categoria_id, $id);
        $stmt->execute();

        if($stmt->affected_rows > 0){
                $retorno = [
                    "status"=> "ok",
                    "mensagem"=> $stmt->affected_rows." registro alterado com sucesso",
                    "data"=> []
                ];
        }else{
                $retorno["mensagem"] = "Nenhum registro foi alterado (dados iguais ou erro)";
        }
        $stmt->close();
        
    } else {
        $retorno["mensagem"] = "Dados incompletos.";
    }
    
}

$conexao->close();
header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
?>
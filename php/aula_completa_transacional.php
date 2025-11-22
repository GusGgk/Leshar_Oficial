<?php
include_once("conexao.php"); 

$retorno = [
    "status"=> "erro",
    "mensagem"=> "Erro desconhecido.",
];

$hora_inicio = '2025-12-01 15:00:00';
$hora_fim = '2025-12-01 16:00:00';
$mensagem = 'Aula de Transação RF06: Agendamento';
$mentor_id = 1; 
$aluno_id = 2;  

$conexao->autocommit(FALSE);

try {
    // 1. Insere a Aula
    $stmt1 = $conexao->prepare("INSERT INTO aula (hora_inicio, hora_fim, mensagem) VALUES (?,?,?)");
    $stmt1->bind_param("sss", $hora_inicio, $hora_fim, $mensagem); 
    $stmt1->execute(); 
    if ($stmt1->affected_rows === 0) {
        throw new Exception("Erro ao inserir a Aula.");
    }
    $id_aula_gerada = $conexao->insert_id;
    $stmt1->close();
    
    $stmt2 = $conexao->prepare("INSERT INTO participante_aula (aula_id, mentor_id, aluno_id) VALUES (?,?,?)");
    $stmt2->bind_param("iii", $id_aula_gerada, $mentor_id, $aluno_id);
    $stmt2->execute(); 
    if ($stmt2->affected_rows === 0) {
        throw new Exception("Erro ao inserir o Participante na Aula. (Verifique se os IDs de mentor e aluno existem)");
    }
    $stmt2->close();
    
    $conexao->commit();
    $retorno = ["status" => "ok", "mensagem" => "Agendamento da aula (ID: $id_aula_gerada) e participante realizado com sucesso!"];

} catch (Exception $e) {
    $conexao->rollback();
    $retorno["mensagem"] = "Transação falhou: " . $e->getMessage();
}

$conexao->autocommit(TRUE);
$conexao->close();

header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
?>
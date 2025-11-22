<?php
include_once("conexao.php"); 
session_start();

$retorno = [
    "status"=> "erro",
    "mensagem"=> "Erro desconhecido.",
];

$participante_aula_id = 4; 
$pontuacao = 4.5;
$data = date('Y-m-d'); 
$mensagem = "Ótima experiência de aprendizado com a transação RF08.";

if ($participante_aula_id <= 0) {
    $retorno["mensagem"] = "ID do Participante da Aula é obrigatório.";
    echo json_encode($retorno);
    exit;
}

$conexao->autocommit(FALSE);

try {
    $stmt1 = $conexao->prepare("INSERT INTO avaliacao (pontuacao, data, mensagem, participante_aula_id) VALUES (?,?,?,?)");
    $stmt1->bind_param("dssi", $pontuacao, $data, $mensagem, $participante_aula_id); 
    $stmt1->execute();
    
    if($stmt1->affected_rows === 0){
        throw new Exception("Erro ao inserir a avaliação (Verifique o ID).");
    }
    $stmt1->close();
    
    $sql_reputacao = "
        SELECT 
            m.id AS mentor_pk, 
            AVG(av.pontuacao) AS nova_reputacao
        FROM avaliacao av
        JOIN participante_aula pa ON av.participante_aula_id = pa.id
        JOIN mentor m ON pa.mentor_id = m.id
        WHERE pa.mentor_id = (SELECT mentor_id FROM participante_aula WHERE id = ?)
        GROUP BY m.id
    ";
    
    $stmt2 = $conexao->prepare($sql_reputacao);
    $stmt2->bind_param("i", $participante_aula_id);
    $stmt2->execute();
    $dados_mentor = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();
    
    if (!$dados_mentor) {
        throw new Exception("Mentor não encontrado para recalcular reputação.");
    }

    $mentor_pk = $dados_mentor['mentor_pk'];
    $nova_reputacao = round($dados_mentor['nova_reputacao']); 

    $stmt3 = $conexao->prepare("UPDATE mentor SET reputacao = ? WHERE id = ?");
    $stmt3->bind_param("ii", $nova_reputacao, $mentor_pk);
    $stmt3->execute();
    $stmt3->close();
    
    $conexao->commit();
    $retorno = [
        "status" => "ok", 
        "mensagem" => "Avaliação registrada e Reputação do Mentor (ID: $mentor_pk) atualizada para: $nova_reputacao.",
        "data" => ["nova_reputacao" => $nova_reputacao]
    ];

} catch (Exception $e) {
    $conexao->rollback();
    $retorno["mensagem"] = "Transação de Avaliação/Reputação falhou! " . $e->getMessage();
}

$conexao->autocommit(TRUE);
$conexao->close();

header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
?>
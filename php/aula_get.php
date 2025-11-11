<?php
include_once("conexao.php");
session_start(); 

$retorno = [
    "status"=> "erro",
    "mensagem"=> "Não encontrou registros",
    "data"=> []
];

if(isset($_GET['id'])){ 
    $id = $_GET['id'];
    $stmt = $conexao->prepare("SELECT * FROM aula WHERE id = ?"); 
    $stmt->bind_param("i", $id);
} else {
    $stmt = $conexao->prepare("
        SELECT 
            a.id, a.hora_inicio, a.hora_fim, a.mensagem,
            pa.id AS participante_aula_id,
            u_aluno.nome AS aluno_nome,
            u_mentor.nome AS mentor_nome
        FROM aula a
        LEFT JOIN participante_aula pa ON a.id = pa.aula_id
        LEFT JOIN aluno al ON pa.aluno_id = al.id
        LEFT JOIN usuario u_aluno ON al.usuario_id = u_aluno.id
        LEFT JOIN mentor m ON pa.mentor_id = m.id
        LEFT JOIN usuario u_mentor ON m.usuario_id = u_mentor.id
        ORDER BY a.hora_inicio DESC
    ");
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
}

$stmt->close();
$conexao->close();

header('Content-Type: application/json;charset=utf-8'); 
echo json_encode($retorno);
?>
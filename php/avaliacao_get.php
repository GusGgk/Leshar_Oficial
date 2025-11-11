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
    $stmt = $conexao->prepare("SELECT * FROM avaliacao WHERE id = ?"); 
    $stmt->bind_param("i", $id);
} else {
    $stmt = $conexao->prepare("
        SELECT 
            av.id, av.pontuacao, av.data, av.mensagem,
            pa.id AS participante_aula_id,
            u_aluno.nome AS aluno_nome,
            u_mentor.nome AS mentor_nome
        FROM avaliacao av
        JOIN participante_aula pa ON av.participante_aula_id = pa.id
        JOIN aluno a ON pa.aluno_id = a.id
        JOIN usuario u_aluno ON a.usuario_id = u_aluno.id
        JOIN mentor m ON pa.mentor_id = m.id
        JOIN usuario u_mentor ON m.usuario_id = u_mentor.id
        ORDER BY av.data DESC
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
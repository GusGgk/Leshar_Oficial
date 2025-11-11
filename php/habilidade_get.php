<?php
include_once("conexao.php");

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $conexao->prepare("SELECT h.id, h.nome, h.descricao, h.categoria_id, c.nome AS categoria_nome FROM habilidade h JOIN categoria_habilidade c ON h.categoria_id = c.id WHERE h.id = ?");
    $stmt->bind_param("i", $id);
} else {
    $stmt = $conexao->prepare("SELECT h.id, h.nome, h.descricao, c.nome AS categoria_nome FROM habilidade h JOIN categoria_habilidade c ON h.categoria_id = c.id ORDER BY h.nome ASC");
}

$stmt->execute();
$resultado = $stmt->get_result();
$tabela = [];
$retorno = [
    "status"=> "erro",
    "mensagem"=> "Não encontrou registros",
    "data"=> []
];

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
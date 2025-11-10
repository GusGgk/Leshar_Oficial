<?php
include_once("conexao.php");


if(isset($_GET['id'])){ // verifica se o id foi passado via GET
    $id = $_GET['id']; // obtém o id via GET
    $stmt = $conexao->prepare("SELECT * FROM aula WHERE id = ?"); // prepara a query statement
    $stmt->bind_param("i", $id); // "i" indica que o parâmetro é um inteiro
} else {
    $stmt = $conexao->prepare("SELECT * FROM aula"); // prepara a query statement sem filtro
}


$stmt->execute(); 
// executa a query

// obtém o resultado da query e armazena na variável resultado
$resultado = $stmt->get_result(); 

// começa a leitura dos resultados
$tabela = []; 

// inicialização do array de retorno
$retorno = [
    "status"=> "",
    "mensagem"=> "",
    "data"=> []
];

if($resultado->num_rows > 0){ // condição se a query retornar registros
    while($linha = $resultado-> fetch_assoc()){ //enquanto houver registros pega uma linha, transforma o resultado em um array associativo e armazene na tabela
        $tabela[] = $linha; // armazena dentro de um array (neste caso tabela)
    }

    $retorno = [
        "status" => "ok",
        "mensagem" => "registros encontrados com sucesso!",
        "data" => $tabela
    ];

} else {
    $retorno = [
        "status" => "erro",
        "mensagem" => "Não encontrou registros",
        "data" => []
    ];
}

$stmt->close();
$conexao->close(); // fecha a conexao com o banco de dados

header('Content-Type: application/json;charset=utf-8'); 
echo json_encode($retorno); // converte o array de retorno em json e exibe na tela
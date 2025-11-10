<?php
include_once("conexao.php");


if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $conexao->prepare("SELECT id, nome, email, bio, localizacao, data_cadastro FROM usuario WHERE id = ?");
    $stmt->bind_param("i", $id);
} else {
    // Se quiser listar todos os usuários (cuidado com privacidade!)
    $stmt = $conexao->prepare("SELECT id, nome, email, bio, localizacao, data_cadastro FROM usuario");
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
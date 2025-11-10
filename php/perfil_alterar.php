<?php
include_once("conexao.php"); // todos os arquivos que precisam de conexao com o banco de dados

$retorno = [
    "status"=> "",
    "mensagem"=> "",
    "data"=> []
];

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];  // Converter para inteiro

    // Atribuição
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $localizacao = $_POST['localizacao'];
    
    $stmt = $conexao->prepare("UPDATE usuario SET nome = ?, email = ?, bio = ?, localizacao = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nome, $email, $bio, $localizacao, $id);
    $stmt->execute(); // executa a query

    if($stmt->affected_rows > 0){
            $retorno = [
                "status"=> "ok",
                "mensagem"=> $stmt->affected_rows."registros inseridos com sucesso",
                "data"=> []
            ];
    }else{
            $retorno = [
                "status"=> "erro",
                "mensagem"=> "0 registros inseridos",
                "data"=> []
            ];
        }
    $stmt->close();
} else {
    // inicialização do array de retorno
    $retorno = [
        "status"=> "erro",
        "mensagem"=> "necessário informar o id para alteração",
        "data"=> []
    ];
}

 // fecha a conexao com o banco de dados
$conexao->close();
header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno); // converte o array de retorno em json e exibe
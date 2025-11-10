<?php
include_once("conexao.php");

// inicialização do array de retorno
$retorno = [
    "status"=> "",
    "mensagem"=> "",
    "data"=> []
];

if(isset($_GET['id'])){ // verifica se o id foi passado via GET
    $id = $_GET['id']; // obtém o id via GET
    $stmt = $conexao->prepare("DELETE FROM usuario WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" indica que o parâmetro é um inteiro
    $stmt->execute(); // executa a query

    if($stmt->affected_rows > 0){
        $retorno = [
            "status"=> "ok",
            "mensagem"=> $stmt->affected_rows."registros modificados com sucesso",
            "data"=> []
        ];
    }else{
        $retorno = [
            "status"=> "erro",
            "mensagem"=> "Nenhum registro foi modificado",
            "data"=> []
        ];
    }

    $stmt->close();
} else {
    // inicialização do array de retorno
    $retorno = [
        "status"=> "erro",
        "mensagem"=> "necessário informar o id para exclusão",
        "data"=> []
    ];
}
$conexao->close(); // fecha a conexao com o banco de dados

header('Content-Type: application/json;charset=utf-8'); 
echo json_encode($retorno); // converte o array de retorno em json e exibe na tela

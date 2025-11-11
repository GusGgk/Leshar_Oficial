<?php
include_once("conexao.php"); // todos os arquivos que precisam de conexao com o banco de dados
session_start(); 


// inicialização do array de retorno
$retorno = [
    "status"=> "",
    "mensagem"=> "",
    "data"=> []
];

// atribuicao

$email_aluno = $_POST['email_aluno'];
$aula_id = $_POST['aula_id'];

$mentor_id = $_SESSION['user_id'];


$stmt_busca = $conexao->prepare("
    SELECT a.id AS aluno_id
    FROM usuario u
    INNER JOIN aluno a ON u.id = a.usuario_id
    WHERE u.email = ?
");
$stmt_busca->bind_param("s", $email_aluno);
$stmt_busca->execute();
$resultado_busca = $stmt_busca->get_result();

if($resultado_busca->num_rows > 0) {
    $aluno_data = $resultado_busca->fetch_assoc();
    $aluno_id = $aluno_data['aluno_id'];
    
    //SEGUNDA ETAPA inserir na tabela participante_aula com o aluno_id encontrado
    $stmt_insert = $conexao->prepare("INSERT INTO participante_aula (aula_id, mentor_id, aluno_id) VALUES (?,?,?)");
    $stmt_insert->bind_param("iii", $aula_id, $mentor_id, $aluno_id); // "iii" = 3 inteiros
    $stmt_insert->execute();
    
    if($stmt_insert->affected_rows > 0) {
        $retorno = [
            "status"=> "ok",
            "mensagem"=> $stmt_insert->affected_rows." registro(s) inserido(s) com sucesso",
            "data"=> []
        ];
    } else {
        $retorno = [
            "status"=> "erro",
            "mensagem"=> "Erro ao inserir participante na aula",
            "data"=> []
        ];
    }
    $stmt_insert->close();
} else {
    $retorno = [
        "status"=> "erro",
        "mensagem"=> "Email do aluno não encontrado ou sem perfil de aluno",
        "data"=> []
    ];
}

$stmt_busca->close();
$conexao->close();
header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
?>

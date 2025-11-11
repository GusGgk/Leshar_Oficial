<?php
include_once("conexao.php"); 
session_start(); 


$retorno = [
    "status"=> "erro",
    "mensagem"=> "Erro desconhecido.",
    "data"=> []
];

//atribuicao com verificação
$email_aluno = $_POST['email_aluno'] ?? '';
$aula_id = (int)($_POST['aula_id'] ?? 0);
$usuario_id_logado = (int)($_SESSION['user_id'] ?? 0);

if (empty($email_aluno) || $aula_id <= 0 || $usuario_id_logado <= 0) {
    $retorno["mensagem"] = "Dados incompletos (email, aula ou usuário não logado).";
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($retorno);
    exit;
}

$stmt_mentor = $conexao->prepare("SELECT id FROM mentor WHERE usuario_id = ?");
$stmt_mentor->bind_param("i", $usuario_id_logado);
$stmt_mentor->execute();
$resultado_mentor = $stmt_mentor->get_result();

if ($resultado_mentor->num_rows == 0) {
    $retorno["mensagem"] = "Erro: Perfil de mentor não encontrado para o usuário logado.";
    $stmt_mentor->close();
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($retorno);
    exit;
}

$mentor_data = $resultado_mentor->fetch_assoc();
$mentor_id_correto = (int)$mentor_data['id']; 
$stmt_mentor->close();

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
    $stmt_insert = $conexao->prepare("INSERT INTO participante_aula (aula_id, mentor_id, aluno_id) VALUES (?,?,?)");
    $stmt_insert->bind_param("iii", $aula_id, $mentor_id_correto, $aluno_id);
    $stmt_insert->execute();
    
    if($stmt_insert->affected_rows > 0) {
        $retorno = [
            "status"=> "ok",
            "mensagem"=> $stmt_insert->affected_rows." registro(s) inserido(s) com sucesso",
            "data"=> []
        ];
    } else {
        $retorno["mensagem"] = "Erro ao inserir participante na aula: " . $stmt_insert->error;
    }
    $stmt_insert->close();
} else {
    $retorno["mensagem"] = "Email do aluno não encontrado ou sem perfil de aluno";
}

$stmt_busca->close();
$conexao->close();
header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
?>
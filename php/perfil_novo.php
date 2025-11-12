<?php
include_once("conexao.php");
session_start();


$retorno = [
    "status" => "erro",
    "mensagem" => "",
    "data" => []
];

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'ADM') {
    $retorno["mensagem"] = "Acesso negado. Requer privilégios de Administrador.";
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($retorno);
    exit;
}

// erificar se todos os campos foram enviados
$camposObrigatorios = ['nome', 'email'];
foreach ($camposObrigatorios as $campo) {
    if (!isset($_POST[$campo]) || strlen(trim($_POST[$campo])) === 0) {
        $retorno["mensagem"] = "O campo '$campo' é obrigatório.";
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($retorno);
        exit;
    }
}

// atribuir variáveis
$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$bio = trim($_POST['bio']);
$localizacao = trim($_POST['localizacao']);


// Verificar se o e-mail já existe
$checkStmt = $conexao->prepare("SELECT id FROM usuario WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    $retorno["mensagem"] = "Erro: já existe um usuário cadastrado com este e-mail.";
} else {
    $stmt = $conexao->prepare("INSERT INTO usuario (nome, email, senha, bio, localizacao, data_cadastro)
                               VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $nome, $email,$senha, $bio, $localizacao);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $retorno = [
            "status" => "ok",
            "mensagem" => "Usuário criado com sucesso!",
            "data" => []
        ];
    } else {
        $retorno["mensagem"] = "Erro ao inserir usuário no banco de dados.";
    }

    $stmt->close();
}

$checkStmt->close();
$conexao->close();

header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
?>

<?php
include_once("conexao.php");
session_start();

// Estrutura padrão de retorno
$retorno = [
    "status" => "erro",
    "mensagem" => "",
    "data" => []
];

// 1️⃣ Verificar sessão e tipo de usuário
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'ADM') {
    $retorno["mensagem"] = "Acesso negado. Requer privilégios de Administrador.";
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode($retorno);
    exit;
}

// 2️⃣ Verificar se todos os campos foram enviados
$camposObrigatorios = ['nome', 'email'];
foreach ($camposObrigatorios as $campo) {
    if (!isset($_POST[$campo]) || strlen(trim($_POST[$campo])) === 0) {
        $retorno["mensagem"] = "O campo '$campo' é obrigatório.";
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($retorno);
        exit;
    }
}

// 3️⃣ Sanitizar e atribuir variáveis
$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$bio = trim($_POST['bio']);
$localizacao = trim($_POST['localizacao']);
$tipo_usuario = trim($_POST['tipo_usuario']);


// 4️⃣ Verificar se o e-mail já existe
$checkStmt = $conexao->prepare("SELECT id FROM usuario WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    $retorno["mensagem"] = "Erro: já existe um usuário cadastrado com este e-mail.";
} else {
    $stmt = $conexao->prepare("INSERT INTO usuario (nome, email, bio, localizacao, tipo_usuario, data_cadastro)
                               VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $nome, $email, $bio,$tipo_usuario, $localizacao);
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

// 6️⃣ Retorno final
header('Content-Type: application/json;charset=utf-8');
echo json_encode($retorno);
?>

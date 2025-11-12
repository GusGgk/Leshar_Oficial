<?php
include_once("conexao.php");

$retorno = [
    "status" => "",
    "mensagem" => ""
];

if (isset($_POST['CadastroNome']) && isset($_POST['CadastroEmail']) && isset($_POST['CadastroSenha'])) {

    $nome = $_POST['CadastroNome'];
    $email = $_POST['CadastroEmail'];
    $senha = password_hash($_POST['CadastroSenha'], PASSWORD_DEFAULT);

    $sql = $conexao->prepare("SELECT id FROM usuario WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $resultado = $sql->get_result();

    if ($resultado->num_rows > 0) {
        $retorno['status'] = "erro";
        $retorno['mensagem'] = 'Email já cadastrado.';
        $sql->close();
    } else {
        $sql->close(); 

        $sql_insert = $conexao->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
        $sql_insert->bind_param("sss", $nome, $email, $senha);

        if ($sql_insert->execute()) {
            
            $id_novo_usuario = $conexao->insert_id;
            $sql_insert->close();

            $sql_mentor = $conexao->prepare("INSERT INTO mentor (usuario_id) VALUES (?)");
            $sql_mentor->bind_param("i", $id_novo_usuario);
            
            if(!$sql_mentor->execute()){
            }
            $sql_mentor->close();

         
            $sql_aluno = $conexao->prepare("INSERT INTO aluno (usuario_id) VALUES (?)");
            $sql_aluno->bind_param("i", $id_novo_usuario);
            
            if(!$sql_aluno->execute()){
            }
            $sql_aluno->close();

            $retorno['status'] = "ok";
            $retorno['mensagem'] = "Usuário cadastrado e vinculado como Aluno e Mentor com sucesso.";

        } else {
            $retorno['status'] = "erro";
            $retorno['mensagem'] = "Erro ao inserir usuário no banco.";
        }
    }
} else {
    $retorno['status'] = "erro";
    $retorno['mensagem'] = "Dados de cadastro incompletos.";
}

// Fecha a conexão geral
$conexao->close();

header('Content-Type: application/json; charset=utf-8');
echo json_encode($retorno);
?>
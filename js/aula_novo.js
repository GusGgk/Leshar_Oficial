let idAulaAtual = null;

document.addEventListener("DOMContentLoaded", () => {
});

document.getElementById("enviar").addEventListener("click", function(){
    nova_aula();
});

document.getElementById("btn_salvar_aluno").addEventListener("click", function(){
    novo_email_aluno();
});

async function nova_aula(){
    var hora_inicio = document.getElementById("hora_inicio").value;
    var hora_fim = document.getElementById("hora_fim").value;
    var mensagem = document.getElementById("mensagem").value;

    if(hora_inicio.length > 0 && hora_fim.length > 0 && mensagem.length > 0){
        const fd = new FormData();
        fd.append('hora_inicio', hora_inicio);
        fd.append('hora_fim', hora_fim);
        fd.append('mensagem', mensagem);

        const retorno = await fetch('../php/aula_novo.php', {
            method: 'POST',
            body: fd
        });
        
        const resposta = await retorno.json();

        if (resposta.status === "ok") {
            idAulaAtual = resposta.data.id; 

            alert("Aula criada! ID: " + idAulaAtual + ". Agora informe o e-mail do aluno.");
            
            // Mostramos a div para o usuário digitar o e-mail
            document.getElementById("segunda_div").style.display = 'flex';
            
            // Opcional: Esconder o botão de criar aula para não duplicar
            document.getElementById("enviar").style.display = 'none';

        } else {
            alert("Erro ao criar aula: " + resposta.mensagem);
        }
    } else {
        alert("Preencha os campos da aula.");
    }
}

async function novo_email_aluno(){
    var email_aluno = document.getElementById("email_aluno").value;

    // Verificamos se temos o ID da aula e o e-mail
    if(email_aluno.length > 0 && idAulaAtual !== null) {
        
        const fd = new FormData();
        fd.append('email_aluno', email_aluno);
        fd.append('aula_id', idAulaAtual); // ENVIA O ID DA AULA PRO PHP FAZER A CONSULTA DO ID COM OS JOINS LÁ

        const retorno = await fetch('../php/participante_aula_novo.php', {
            method: 'POST',
            body: fd
        });

        const resposta = await retorno.json();

        if (resposta.status === 'ok'){
            alert('Sucesso! Aluno vinculado à aula ' + idAulaAtual);
            window.location.href = '../aulas/index.html'; 
        } else {
            alert('Erro: ' + resposta.mensagem);
        }
    } else {
        alert("Preencha o e-mail ou verifique se a aula foi criada.");
    }
}
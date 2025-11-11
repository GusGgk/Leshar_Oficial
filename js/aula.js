document.addEventListener("DOMContentLoaded", () => {
    valida_sessao();
    inserirBotao();
    carregarLista();

    document.getElementById("novo").addEventListener("click", () => {
        window.location.href = "aula_novo.html";  
    });
    document.getElementById("sair").addEventListener("click", () => {
        logoff();
    });
});

async function logoff() {
    const retorno = await fetch('../php/logoff.php');
    const resposta = await retorno.json();
    if (resposta.status == "ok") {
        alert("Você saiu do sistema.");
        window.location.href = "../login/";
    }  
}   

function inserirBotao(){
    var botao = "";
    botao = "<button id='novo'> Novo Registro </button>";
    document.getElementById("titulo").innerHTML += botao;
}
async function excluir(id) {
    const retorno = await fetch('../php/aula_excluir.php?id=' + id);
    const resposta = await retorno.json();
    if (resposta.status == "ok") {
        alert("Sucesso! " + resposta.mensagem);
        window.location.reload();
    } else {
        alert("Erro! " + resposta.mensagem);
    }
}

async function carregarLista(){
    const retorno = await fetch('../php/aula_get.php');
    const resposta = await retorno.json();
    if (resposta.status == "ok") {
        var html = `<table>
        <thead>
        <tr>
        <th>Hora de Início</th>
        <th>Hora de Término</th>
        <th>Descrição/Mensagem da aula</th>
        <th>#</th>
        </tr>
        </thead>
        <tbody>`;
    // farei um laço para preencher as linhas
    for(var i=0; i < resposta.data.length; i++){
        var objeto = resposta.data[i];

        html += 
        `
        <tr>
        <td>${objeto.hora_inicio}</td>
        <td>${objeto.hora_fim}</td>
        <td>${objeto.mensagem}</td>
        <td>
            <a href="aula_alterar.html?id=${objeto.id}">Alterar</a>
            <a href="#" onclick='excluir(${objeto.id})'>Excluir</a>
        </td>
        </tr>
        `;
    }
    html += `</tbody>
            </table>`;
    document.getElementById("lista").innerHTML = html;
    }else{
        alert("Erro!" + resposta.mensagem);
    }
}
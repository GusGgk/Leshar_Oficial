document.addEventListener("DOMContentLoaded", () => {
    valida_sessao();
    inserirBotao(); 
    carregarLista();

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
    const tituloDiv = document.getElementById("titulo");
    if(tituloDiv) {
        tituloDiv.innerHTML += botao;
        document.getElementById("novo").addEventListener("click", () => {
            window.location.href = "avaliacao_novo.html";  
        });
    }
}
async function excluir(id) {
    const retorno = await fetch('../php/avaliacao_excluir.php?id=' + id);
    const resposta = await retorno.json();
    if (resposta.status == "ok") {
        alert("Sucesso! " + resposta.mensagem);
        window.location.reload();
    } else {
        alert("Erro! " + resposta.mensagem);
    }
}

async function carregarLista(){
    const retorno = await fetch('../php/avaliacao_get.php');
    const resposta = await retorno.json();
    if (resposta.status == "ok") {
        var html = `<table>
        <thead>
        <tr>
        <th>Pontuação</th>
        <th>Data</th>
        <th>Observação</th>
        <th>Aluno</th>
        <th>Mentor</th>
        <th>#</th>
        </tr>
        </thead>
        <tbody>`;
    
    for(var i=0; i < resposta.data.length; i++){
        var objeto = resposta.data[i];
        html += 
        `
        <tr>
        <td>${objeto.pontuacao}</td>
        <td>${objeto.data}</td>
        <td>${objeto.mensagem || '---'}</td> <td>${objeto.aluno_nome}</td> <td>${objeto.mentor_nome}</td> <td>
            <a href="avaliacao_alterar.html?id=${objeto.id}">Alterar</a>
            <a href="#" onclick='excluir(${objeto.id})'>Excluir</a>
        </td>
        </tr>
        `;
    }
    html += `</tbody>
            </table>`;
    document.getElementById("lista").innerHTML = html;
    }else{
        document.getElementById("lista").innerHTML = `<p style="color: white; text-align: center;">${resposta.mensagem}</p>`;
    }
}
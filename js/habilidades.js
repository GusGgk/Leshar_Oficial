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
    botao = "<button id='novo'> Nova Habilidade </button>";
    document.getElementById("titulo").innerHTML += botao;
    
    document.getElementById("novo").addEventListener("click", () => {
        window.location.href = "habilidade_nova.html";  
    });
}

async function excluir(id) {
    if (!confirm("Tem certeza que deseja excluir esta habilidade?")) {
        return;
    }
    
    const retorno = await fetch('../php/habilidade_excluir.php?id=' + id);
    const resposta = await retorno.json();
    if (resposta.status == "ok") {
        alert("Sucesso! " + resposta.mensagem);
        window.location.reload();
    } else {
        alert("Erro! " + resposta.mensagem);
    }
}

async function carregarLista(){
    const retorno = await fetch('../php/habilidade_get.php');
    const resposta = await retorno.json();
    
    if (resposta.status == "ok") {
        var html = `<table>
        <thead>
        <tr>
        <th>Habilidade (Nome)</th>
        <th>Descrição</th>
        <th>Categoria</th>
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>`;

    for(var i=0; i < resposta.data.length; i++){
        var objeto = resposta.data[i];

        html += 
        `
        <tr>
        <td>${objeto.nome}</td>
        <td>${objeto.descricao}</td>
        <td>${objeto.categoria_nome}</td>
        <td>
            <a href="habilidade_alterar.html?id=${objeto.id}">Alterar</a>
            <a href="#" onclick='excluir(${objeto.id})'>Excluir</a>
        </td>
        </tr>
        `;
    }
    html += `</tbody>
            </table>`;
    document.getElementById("lista").innerHTML = html;
    
    } else if (resposta.status === "erro" && resposta.mensagem === "Não autenticado") {
        alert("Sua sessão expirou. Faça login novamente.");
        window.location.href = "../login/";
    } else {
        document.getElementById("lista").innerHTML = `<p style="color: white; text-align: center;">${resposta.mensagem}</p>`;
    }
}
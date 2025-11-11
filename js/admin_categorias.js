document.addEventListener("DOMContentLoaded", () => {
    valida_sessao(); 
    inserirBotao(); 
    carregarLista();

    document.getElementById("sair").addEventListener("click", () => {
        logoff();
    });
});


async function logoff() {
    const retorno = await fetch('../php/logoff.php', { credentials: "include" });
    const resposta = await retorno.json();
    if (resposta.status == "ok") {
        alert("Você saiu do sistema.");
        window.location.href = "../login/";
    }  
}   

async function inserirBotao(){
    try{
        const r = await fetch('../php/valida_sessao_admin.php', { credentials: 'include' });
        const j = await r.json();
        if(j.status === 'ok'){
            const botao = "<button id='novo'> Novo Registro </button>";
            document.getElementById("titulo").innerHTML += botao;
            document.getElementById("novo").addEventListener("click", () => {
                window.location.href = "categoria_nova.html";  
            });
        }
    } catch(e){
    }
}

async function excluir(id) {
    if (!confirm("Tem certeza que deseja excluir esta categoria?")) {
        return;
    }

    const retorno = await fetch('../php/categoria_excluir.php?id=' + id, { credentials: "include" });
    const resposta = await retorno.json();
    if (resposta.status == "ok") {
        alert("Sucesso! " + resposta.mensagem);
        window.location.reload();
    } else {
        alert("Erro! " + resposta.mensagem);
    }
}

async function carregarLista(){
    const retorno = await fetch('../php/categoria_get.php', { credentials: "include" });
    const resposta = await retorno.json();
    
    if (resposta.status == "ok") {
        var html = `<table>
        <thead>
        <tr>
        <th>ID</th>
        <th>Nome da Categoria</th>
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>`;
    
        for(var i=0; i < resposta.data.length; i++){
            var objeto = resposta.data[i];
            html += 
            `
            <tr>
            <td>${objeto.id}</td>
            <td>${objeto.nome}</td>
            <td>
                <a href="categoria_alterar.html?id=${objeto.id}">Alterar</a>
                <a href="#" onclick='excluir(${objeto.id})'>Excluir</a>
            </td>
            </tr>
            `;
        }
        html += `</tbody>
                </table>`;
        document.getElementById("lista").innerHTML = html;
    } else {
        document.getElementById("lista").innerHTML = `<p style='color:white;'>${resposta.mensagem}</p>`;
    }
}
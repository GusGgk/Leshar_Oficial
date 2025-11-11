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
    if (resposta.status == "Ok") {
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
                window.location.href = "perfil_novo.html";  
            });
        }
    }catch(e){
        // silencioso
    }
}
async function excluir(id) {
    const retorno = await fetch('../php/perfil_excluir.php?id=' + id);
    const resposta = await retorno.json();
    if (resposta.status == "ok") {
        alert("Sucesso! " + resposta.mensagem);
        window.location.reload();
    } else {
        alert("Erro! " + resposta.mensagem);
    }
}

async function carregarLista(){
    const retorno = await fetch('../php/perfil_get.php');
    const resposta = await retorno.json();
    if (resposta.status == "ok") {
        var html = `<table>
        <thead>
        <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Bio</th>
        <th>Localização</th>
        <th>Data de Cadastro</th>
        <th>#</th>
        </tr>
        </thead>
        <tbody>`;

    for(var i=0; i < resposta.data.length; i++){
        var objeto = resposta.data[i];
        html += `
        <tr>
        <td>${objeto.nome}</td>
        <td>${objeto.email}</td>
        <td>${objeto.bio}</td>
        <td>${objeto.localizacao}</td>
        <td>${objeto.data_cadastro}</td>
        <td>
            <a href="perfil_alterar.html?id=${objeto.id}">Alterar</a>
            <a href="#" onclick='excluir(${objeto.id})'>Excluir</a>
        </td>
        </tr>`;
}
    html += `</tbody>
            </table>`;
    document.getElementById("lista").innerHTML = html;
    }else{
        alert("Erro!" + resposta.mensagem);
    }
}
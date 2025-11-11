document.addEventListener("DOMContentLoaded", () => {
    valida_sessao(); 
    valida_sessao_admin_especifica(); 
    inserirBotao(); 
    carregarLista();
    document.getElementById("sair").addEventListener("click", () => {
        logoff();
    });
});

// Função de validação de admin 
async function valida_sessao_admin_especifica() {
  try {
    const retorno = await fetch("../php/valida_sessao_admin.php", { credentials: "include" });
    const resposta = await retorno.json();

    if (resposta.status === "erro") {
      alert("Acesso negado. Apenas administradores podem ver esta página.");
      window.location.href = "../home/"; // Manda para home se não for admin
    }
  } catch (err) {
    console.error("Erro ao validar sessão:", err);
    window.location.href = "../login/";
  }
}

// Função de logoff 
async function logoff() {
    const retorno = await fetch('../php/logoff.php');
    const resposta = await retorno.json();
    if (resposta.status == "ok") { 
        alert("Você saiu do sistema.");
        window.location.href = "../login/";
    }  
}   

// Função para inserir botão 
function inserirBotao(){
    var botao = "";
    botao = "<button id='novo'> Novo Registro </button>";
    document.getElementById("titulo").innerHTML += botao;
    document.getElementById("novo").addEventListener("click", () => {
        window.location.href = "categoria_nova.html";  
    });
}

// Função para excluir 
async function excluir(id) {
    if (!confirm("Tem certeza que deseja excluir esta categoria? Isso pode afetar usuários que a utilizam.")) {
        return;
    }
    const retorno = await fetch('../php/categoria_excluir.php?id=' + id);
    const resposta = await retorno.json();
    if (resposta.status == "ok") {
        alert("Sucesso! " + resposta.mensagem);
        window.location.reload();
    } else {
        alert("Erro! " + resposta.mensagem);
    }
}

// Função para carregar a lista 
async function carregarLista(){
    const retorno = await fetch('../php/categoria_get.php'); 
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
            <a href="#" onclick='excluir(${objeto.id})'>Excluir</a>
        </td>
        </tr>
        `;
    }
    html += `</tbody>
            </table>`;
    document.getElementById("lista").innerHTML = html;
    }else{
        alert("Erro ao carregar lista! " + resposta.mensagem); 
        document.getElementById("lista").innerHTML = `<p style='color:white;'>${resposta.mensagem}</p>`;
    }
}
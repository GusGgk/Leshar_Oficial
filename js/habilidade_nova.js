document.addEventListener("DOMContentLoaded", () => {
    valida_sessao();
    carregarCategorias(); 

    document.getElementById("enviar").addEventListener("click", function(){
        novo();
    });
});

async function carregarCategorias() {
    const retorno = await fetch('../php/categoria_get.php');
    const resposta = await retorno.json();
    const select = document.getElementById("categoria");

    if (resposta.status === "ok") {
        select.innerHTML = '<option value="">Selecione uma categoria</option>';
        resposta.data.forEach(cat => {
            select.innerHTML += `<option value="${cat.id}">${cat.nome}</option>`;
        });
    } else {
        select.innerHTML = '<option value="">Erro ao carregar categorias</option>';
        alert("Erro ao carregar categorias: " + resposta.mensagem);
    }
}

async function novo(){
    var nome = document.getElementById("nome").value;
    var descricao = document.getElementById("descricao").value;
    var categoria_id = document.getElementById("categoria").value;

    if(nome.length > 0 && descricao.length > 0 && categoria_id.length > 0){
        const fd = new FormData();
        fd.append('nome', nome);
        fd.append('descricao', descricao);
        fd.append('categoria_id', categoria_id);

        const retorno = await fetch('../php/habilidade_novo.php', {
            method: 'POST',
            body: fd
        });
        const resposta = await retorno.json();
        if (resposta.status === "ok") {
            alert("Sucesso! " + resposta.mensagem);
            window.location.href = "index.html";
        }else{
            alert("Erro! " + resposta.mensagem);
        }
    } else {
        alert("É necessário preencher todos os campos.");
    }
}
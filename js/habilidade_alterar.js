document.addEventListener("DOMContentLoaded", () =>{
    const url = new URLSearchParams(window.location.search);
    var id = url.get("id");

    if(id){
        carregarCategorias(id); 
    } else{
        alert("É necessário informar um ID.");
        window.location.href = "index.html";
    }
    
    document.getElementById("enviar").addEventListener("click", function(){
        fase2();
    });
});

//guarda a categoria original
var categoriaOriginalId = null;

async function carregarCategorias(idHabilidade) {
    const retorno = await fetch('../php/categoria_get.php');
    const resposta = await retorno.json();
    const select = document.getElementById("categoria");

    if (resposta.status === "ok") {
        select.innerHTML = '<option value="">Selecione uma categoria</option>';
        resposta.data.forEach(cat => {
            select.innerHTML += `<option value="${cat.id}">${cat.nome}</option>`;
        });
        
        //busca os dados da habilidade
        fase1(idHabilidade);
    } else {
        alert("Erro fatal ao carregar categorias. " + resposta.mensagem);
    }
}

//busca dados da Habilidade
async function fase1(id){
    const retorno = await fetch('../php/habilidade_get.php?id='+id);
    const resposta = await retorno.json();
        if (resposta.status == "ok") {
            alert("Sucesso! "+ "Dados da habilidade carregados."); 
            
            const reg = resposta.data[0];
            document.getElementById("nome").value = reg.nome;
            document.getElementById("descricao").value = reg.descricao;
            document.getElementById("id").value = reg.id;
            document.getElementById("categoria").value = reg.categoria_id;
            
        }else{
            alert("Erro! " + resposta.mensagem);
            window.location.href = "index.html";
        }
}

//envia os dados alterados
async function fase2(){
    var nome = document.getElementById("nome").value;
    var descricao = document.getElementById("descricao").value;
    var categoria_id = document.getElementById("categoria").value;
    var id = document.getElementById("id").value;

    if(nome.length > 0 && descricao.length > 0 && categoria_id.length > 0){
        const fd = new FormData();
        fd.append('nome', nome);
        fd.append('descricao', descricao);
        fd.append('categoria_id', categoria_id);

        const retorno = await fetch('../php/habilidade_alterar.php?id=' + id, {
            method: 'POST',
            body: fd
        });
        const resposta = await retorno.json();
        if (resposta.status == "ok") {
            alert("Sucesso! " + resposta.mensagem);
            window.location.href = "index.html";
        }else{
            alert("Erro! " + resposta.mensagem);
        }
    } else {
        alert("É necessário preencher todos os campos.");
    }
}
document.addEventListener("DOMContentLoaded", () =>{
    valida_sessao();
    valida_sessao_admin_especifica();

    const url = new URLSearchParams(window.location.search);
    var id = url.get("id");

    if(id){
        fase1(id);
    } else{
        alert("É necessário ao menos informar um ID.");
        window.location.href = "categorias.html";
    }
    
    document.getElementById("enviar").addEventListener("click", function(){
        fase2();
    });
});

async function valida_sessao_admin_especifica() {
  try {
    const retorno = await fetch("../php/valida_sessao_admin.php", { credentials: "include" });
    const resposta = await retorno.json();
    if (resposta.status === "erro") {
      alert("Acesso negado.");
      window.location.href = "../home/"; 
    }
  } catch (err) {
    window.location.href = "../login/";
  }
}


async function fase1(id){
    const retorno = await fetch('../php/categoria_get.php?id='+id);
    const resposta = await retorno.json();
        if (resposta.status == "ok") {
            const reg = resposta.data[0];
            document.getElementById("nome").value = reg.nome;
            document.getElementById("id").value = id;
        }else{
            alert("Erro! " + resposta.mensagem);
        }
}

async function fase2(){
    var nome = document.getElementById("nome").value;
    var id = document.getElementById("id").value;

    if(nome.trim().length > 0){
        const fd = new FormData();
        fd.append('nome', nome);

        const retorno = await fetch('../php/categoria_alterar.php?id=' + id, {
            method: 'POST',
            body: fd
        });
        const resposta = await retorno.json();
        if (resposta.status == "ok") {
            alert("Sucesso! " + resposta.mensagem);
            window.location.href = "categorias.html";
        }else{
            alert("Erro! " + resposta.mensagem);
        }
    } else {
        alert("É necessário preencher o nome.");
    }
}
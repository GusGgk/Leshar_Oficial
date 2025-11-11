document.addEventListener("DOMContentLoaded", () =>{
    const url = new URLSearchParams(window.location.search);
    var id = url.get("id");
    console.log(id);

    if(id){
        fase1(id);
    } else{
        alert("É necessário ao menos informar um ID.");
    }
});

async function fase1(id){
    const retorno = await fetch('../php/avaliacao_get.php?id='+id);
    const resposta = await retorno.json();
        if (resposta.status == "ok") {
            const reg = resposta.data[0];
            
            document.getElementById("pontuacao").value = reg.pontuacao;
            document.getElementById("data").value = reg.data;
            document.getElementById("mensagem").value = reg.mensagem;
            document.getElementById("id").value = id;
        }else{
            alert("Erro! " + resposta.mensagem);
        }
}

document.getElementById("enviar").addEventListener("click", function(){
    fase2();
});

async function fase2(){
    var pontuacao = document.getElementById("pontuacao").value;
    var data = document.getElementById("data").value;
    var mensagem = document.getElementById("mensagem").value;
    var id = document.getElementById("id").value;

    if(pontuacao.length > 0 && data.length > 0){
        const fd = new FormData();
        fd.append('pontuacao', pontuacao);
        fd.append('data', data);
        fd.append('mensagem', mensagem);

        const retorno = await fetch('../php/avaliacao_alterar.php?id=' + id, {
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
        alert("É necessário preencher Pontuação e Data.");
    }
}
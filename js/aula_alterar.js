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
    const retorno = await fetch('../php/aula_get.php?id='+id);
    const resposta = await retorno.json();
        if (resposta.status == "ok") {
            alert("Sucesso! "+ resposta.mensagem);
            const reg = resposta.data[0];
            document.getElementById("hora_inicio").value       = reg.hora_inicio;
            document.getElementById("hora_fim").value    = reg.hora_fim;
            document.getElementById("mensagem").value      = reg.mensagem;
            document.getElementById("id").value         = id;
        }else{
            alert("Erro! " + resposta.mensagem);
        }
}



document.getElementById("enviar").addEventListener("click", function(){
    fase2();
});

async function fase2(){
    var hora_inicio = document.getElementById("hora_inicio").value;
    var hora_fim = document.getElementById("hora_fim").value;
    var mensagem = document.getElementById("mensagem").value;
    var id = document.getElementById("id").value;

    if(hora_inicio.length > 0 && hora_fim.length > 0 && mensagem.length > 0){
        const fd = new FormData();
        fd.append('hora_inicio', hora_inicio);
        fd.append('hora_fim', hora_fim);
        fd.append('mensagem', mensagem);

        const retorno = await fetch('../php/aula_alterar.php?id=' + id, {
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
        alert("É necessário preencher todos os campos.")
    }
}

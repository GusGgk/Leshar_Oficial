document.addEventListener("DOMContentLoaded", () => {
    valida_sessao();
});

document.getElementById("enviar").addEventListener("click", function(){
    novo();
});

async function novo(){
    var hora_inicio = document.getElementById("hora_inicio").value;
    var hora_fim = document.getElementById("hora_fim").value;
    var mensagem = document.getElementById("mensagem").value;

    if(hora_inicio.length > 0 && hora_fim.length > 0 && mensagem.length > 0){
        const fd = new FormData();
        fd.append('hora_inicio', hora_inicio);
        fd.append('hora_fim', hora_fim);
        fd.append('mensagem', mensagem);

        const retorno = await fetch('../php/avaliacao_novo.php', {
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
        alert("É necessário preencher todos os campos.")
    }
}

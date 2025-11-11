document.addEventListener("DOMContentLoaded", () => {
    valida_sessao();
    
    document.getElementById('data').valueAsDate = new Date();
    //pega o id da url
    const url = new URLSearchParams(window.location.search);
    const pa_id = url.get("pa_id"); // pa_id = participante_aula_id

    if(pa_id){
        //preenche o campo
        const campoId = document.getElementById("participante_aula_id");
        campoId.value = pa_id;
        campoId.readOnly = true; 
    }

});

document.getElementById("enviar").addEventListener("click", function(){
    novo();
});

async function novo(){
    var pontuacao = document.getElementById("pontuacao").value;
    var data = document.getElementById("data").value;
    var mensagem = document.getElementById("mensagem").value;
    var participante_aula_id = document.getElementById("participante_aula_id").value;

    if(pontuacao.length > 0 && data.length > 0 && participante_aula_id > 0){
        const fd = new FormData();
        fd.append('pontuacao', pontuacao);
        fd.append('data', data);
        fd.append('mensagem', mensagem);
        fd.append('participante_aula_id', participante_aula_id);

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
        alert("É necessário preencher Pontuação, Data e o ID da Aula/Participante.");
    }
}
document.addEventListener("DOMContentLoaded", () => {
    valida_sessao();
});

document.getElementById("enviar").addEventListener("click", function(){
    novo();
});

async function novo(){
    var nome = document.getElementById("nome").value;
    var email = document.getElementById("email").value;
    var bio = document.getElementById("bio").value;
    var localizacao = document.getElementById("localizacao").value;

    if(nome.length > 0 && email.length > 0 && bio.length > 0 && localizacao.length > 0){
        const fd = new FormData();
        fd.append('nome', nome);
        fd.append('email', email);
        fd.append('bio', bio);
        fd.append('localizacao', localizacao);

        const retorno = await fetch('../php/perfil_novo.php', {
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

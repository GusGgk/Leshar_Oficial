document.addEventListener("DOMContentLoaded", async () => {
    valida_sessao();
    try{
        const r = await fetch('../php/valida_sessao_admin.php', { credentials: 'include' });
        const j = await r.json();
        if(j.status !== 'ok'){
            alert('Acesso restrito a administradores.');
            window.location.href = 'index.html';
        }
    }catch(e){
        window.location.href = 'index.html';
    }
});

document.getElementById("enviar").addEventListener("click", function(){
    novo();
});

async function novo(){
    var nome = document.getElementById("nome").value;
    var email = document.getElementById("email").value;
    var bio = document.getElementById("bio").value;
    var localizacao = document.getElementById("localizacao").value;
    var tipo_usuario = document.getElementById("tipo_usuario").value;

    if(nome.length > 0 && email.length > 0 && bio.length > 0 && localizacao.length > 0 && tipo_usuario > 0){
        const fd = new FormData();
        fd.append('nome', nome);
        fd.append('email', email);
        fd.append('bio', bio);
        fd.append('localizacao', localizacao);
        fd.append('tipo_usuario', tipo_usuario);

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

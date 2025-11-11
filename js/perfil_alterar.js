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
    const retorno = await fetch('../php/perfil_get.php?id='+id);
    const resposta = await retorno.json();
    if (resposta.status == "ok") {
        const reg = resposta.data[0];
        // Mudanças aqui:
        document.getElementById("nome").value = reg.nome;
        document.getElementById("email").value = reg.email;
        document.getElementById("bio").value = reg.bio;
        document.getElementById("localizacao").value = reg.localizacao;
        // data_cadastro é apenas para exibição (read-only)
        document.getElementById("id").value = id;
    }
}



document.getElementById("enviar").addEventListener("click", function(){
    fase2();
});

async function fase2(){
    var nome = document.getElementById("nome").value;
    var email = document.getElementById("email").value;
    var bio = document.getElementById("bio").value;
    var localizacao = document.getElementById("localizacao").value;
    var tipo_usuario = document.getElementById('tipo_usuario').value;
    var id = document.getElementById("id").value;

    if(nome.length > 0 && email.length > 0 && bio.length > 0 && localizacao.length > 0 && tipo_usuario.length){
        const fd = new FormData();
        fd.append('nome', nome);
        fd.append('email', email);
        fd.append('bio', bio);
        fd.append('localizacao', localizacao);
        fp.append('tipo_usuario', tipo_usuario)

        const retorno = await fetch('../php/perfil_alterar.php?id=' + id, {
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

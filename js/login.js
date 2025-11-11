document.getElementById('entrar').addEventListener('click', (e) =>{
    e.preventDefault();
    verificarLogin();
});

document.getElementById('cadastrar').addEventListener('click', (e) =>{
    e.preventDefault();
    cadastrarUsuario();
});

async function verificarLogin(){
    var email = document.getElementById('LoginEmail').value;
    var senha = document.getElementById('LoginSenha').value;
    
    const fd = new FormData();
    fd.append('LoginEmail', email);
    fd.append('LoginSenha', senha);

    try{
        const retorno = await fetch("../php/login.php",{
            method: "POST",
            body: fd
        });
        const resposta = await retorno.json();

        if (resposta.status == "ok") {
            alert("Login realizado com sucesso!");
            if (resposta.data.tipo_usuario == "ADM") {
                window.location.href = "../admin/"; 
            } else {
                window.location.href = "../home/";
            }
        } else {
            alert (resposta.mensagem || "Credenciais inválidas.");
        }
    }catch(err){
        console.error("Erro no login:", err);
        alert("Não foi possível comunicar com o servidor. Verifique se o Apache está rodando.");
    }
}

async function cadastrarUsuario(){
    var nome = document.getElementById('CadastroNome').value;
    var email = document.getElementById('CadastroEmail').value;
    var senha = document.getElementById('CadastroSenha').value;

    const fd = new FormData();
    fd.append('CadastroNome', nome);
    fd.append('CadastroEmail', email);
    fd.append('CadastroSenha', senha);

    try {
        const retorno = await fetch("../php/cadastro.php",{
            method: "POST",
            body: fd
        });

        const resposta = await retorno.json();
        
        if (resposta.status == "ok") {
            alert("Cadastro realizado com sucesso! Agora faça o login.");
            document.querySelector('.card').classList.remove('flipped');
        } else {
            alert("Erro no cadastro: " + resposta.mensagem);
        }
    } catch (error) {
        console.error("Erro no cadastro:", error);
        alert("Ocorreu um erro ao tentar cadastrar. Por favor, tente novamente mais tarde.");
    }
}

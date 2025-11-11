document.addEventListener("DOMContentLoaded", () => {
    valida_sessao();
    valida_sessao_admin_especifica(); 

    document.getElementById("enviar").addEventListener("click", function(){
        novo();
    });
});

// Função de validação de admin
async function valida_sessao_admin_especifica() {
  try {
    const retorno = await fetch("../php/valida_sessao_admin.php", { credentials: "include" });
    const resposta = await retorno.json();

    if (resposta.status === "erro") {
      alert("Acesso negado. Apenas administradores podem criar categorias.");
      window.location.href = "../home/"; // Manda para home se não for admin
    }
  } catch (err) {
    console.error("Erro ao validar sessão:", err);
    window.location.href = "../login/";
  }
}

// Função novo
async function novo(){
    var nome = document.getElementById("nome").value;

    if(nome.trim().length > 0){
        const fd = new FormData();
        fd.append('nome', nome);

        const retorno = await fetch('../php/categoria_novo.php', { 
            method: 'POST',
            body: fd
        });
        const resposta = await retorno.json();
        if (resposta.status === "ok") {
            alert("Sucesso! " + resposta.mensagem);
            window.location.href = "categorias.html"; 
        }else{
            alert("Erro! " + resposta.mensagem);
        }
    } else {
        alert("É necessário preencher o nome da categoria.")
    }
}
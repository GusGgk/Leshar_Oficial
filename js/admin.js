async function valida_sessao_admin() {
  try {
    const retorno = await fetch("../php/valida_sessao_admin.php", { credentials: "include" });
    const resposta = await retorno.json();

    if (resposta.status === "erro") {
      alert("Acesso negado.")
      window.location.href = "../login/";
    } else {
      montarAtalhos();
    }
  } catch (err) {
    console.error("Erro ao validar sessão:", err);
    window.location.href = "../login/";
  }
}
valida_sessao_admin();

function montarAtalhos(){
  // Cria um pequeno painel de atalhos sem alterar o HTML
  const wrap = document.createElement('div');
  wrap.style.margin = '20px';
  wrap.style.display = 'flex';
  wrap.style.gap = '12px';

  const btnUsuarios = document.createElement('button');
  btnUsuarios.textContent = 'Gerenciar Usuários';
  btnUsuarios.style.padding = '10px 16px';
  btnUsuarios.style.cursor = 'pointer';
  btnUsuarios.onclick = () => { window.location.href = '../perfil/'; };

  const btnAulas = document.createElement('button');
  btnAulas.textContent = 'Gerenciar Aulas';
  btnAulas.style.padding = '10px 16px';
  btnAulas.style.cursor = 'pointer';
  btnAulas.onclick = () => { window.location.href = '../aulas/'; };

  const btnCategorias = document.createElement('button');
  btnCategorias.textContent = 'Gerenciar Categorias';
  btnCategorias.style.padding = '10px 16px';
  btnCategorias.style.cursor = 'pointer';
  btnCategorias.onclick = () => { window.location.href = '../admin/categorias.html'; };

  const btnSair = document.createElement('button');
  btnSair.id = 'sair';
  btnSair.textContent = 'Sair';
  btnSair.style.padding = '10px 16px';
  btnSair.style.cursor = 'pointer';
  btnSair.onclick = async () => {
    try{
      const r = await fetch('../php/logoff.php');
      const j = await r.json();
      if(j.status === 'ok'){
        window.location.href = '../login/';
      }
    }catch(e){
      window.location.href = '../login/';
    }
  };

  wrap.appendChild(btnUsuarios);
  wrap.appendChild(btnAulas);
  wrap.appendChild(btnCategorias);
  wrap.appendChild(btnSair);

  document.body.appendChild(wrap);
}

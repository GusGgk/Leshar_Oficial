async function valida_sessao_adm() {
  try {
    const retorno = await fetch("../php/valida_sessao_adm.php", { credentials: "include" });
    const resposta = await retorno.json();

    if (resposta.status === "erro") {
      window.location.href = "../login/";
    }
  } catch (err) {
    console.error("Erro ao validar sessão do administrador:", err);
    window.location.href = "../login/";
  }
}
valida_sessao_adm();

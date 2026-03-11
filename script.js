// 1. Seleção dos elementos (Apenas uma vez!)
const campoBusca = document.getElementById('busca');
const seletorEspecie = document.getElementById('especie');
const cards = document.querySelectorAll('.animal-card');
const mensagemVazia = document.getElementById('mensagem-vazia');

// 2. A função lógica de filtragem
function filtrarAnimais() {
    const termoBusca = campoBusca.value.toLowerCase();
    const especieSelecionada = seletorEspecie.value.toLowerCase();
    
    let encontrados = 0; 

    cards.forEach(card => {
        // Pega o texto do título do card (nome e espécie)
        const nomeEspecieTexto = card.querySelector('h3').innerText.toLowerCase();

        // Regras do filtro
        const bateBusca = nomeEspecieTexto.includes(termoBusca);
        const bateEspecie = nomeEspecieTexto.includes(especieSelecionada) || especieSelecionada === "";

        // Aplica o resultado visualmente
        if (bateBusca && bateEspecie) {
            card.style.display = "block";
            encontrados++; 
        } else {
            card.style.display = "none";
        }
    });

    // Gerencia a mensagem de "não encontrado"
    if (mensagemVazia) {
        if (encontrados === 0) {
            mensagemVazia.style.display = "block";
        } else {
            mensagemVazia.style.display = "none";
        }
    }
}

// 3. Ouvintes de eventos (Listeners)
campoBusca.addEventListener('input', filtrarAnimais);
seletorEspecie.addEventListener('change', filtrarAnimais);
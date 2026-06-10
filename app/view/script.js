/**
 * PROJETO ADOTAPET - SCRIPT UNIFICADO v3.0 (CORRIGIDO)
 * Integra: Filtro + Menu + Efeitos Tech + Modal de Detalhes
 */

// Usamos window.onload para garantir que o JS só execute após o HTML carregar 100%
window.onload = () => {

    // --- 1. SELEÇÃO DE ELEMENTOS ---
    const campoBusca = document.getElementById('busca');
    const seletorEspecie = document.getElementById('especie');
    const cards = document.querySelectorAll('.animal-card');
    const mensagemVazia = document.getElementById('mensagem-vazia');
    const linksMenu = document.querySelectorAll('header nav ul li a');
    const botoesDetalhes = document.querySelectorAll('.btn-secondary');
    const formFiltro = document.querySelector('.sidebar form');
    const modal = document.getElementById('modal-pet');
    const modalConteudo = document.getElementById('modal-conteudo');

    // --- 2. LÓGICA DE FILTRAGEM (CORRIGIDA) ---
    function filtrarAnimais() {
        // Verificamos se os campos existem antes de pegar o valor
        const termoBusca = campoBusca ? campoBusca.value.toLowerCase() : "";
        const especieSelecionada = seletorEspecie ? seletorEspecie.value.toLowerCase() : "";
        
        let encontrados = 0; 

        cards.forEach(card => {
            const tituloH3 = card.querySelector('h3');
            
            // Se o card não tiver H3, ele pula para o próximo sem dar erro
            if (!tituloH3) return;

            const nomeEspecieTexto = tituloH3.innerText.toLowerCase();
            const bateBusca = nomeEspecieTexto.includes(termoBusca);
            const bateEspecie = nomeEspecieTexto.includes(especieSelecionada) || especieSelecionada === "";

            if (bateBusca && bateEspecie) {
                card.style.display = "block";
                encontrados++; 
            } else {
                card.style.display = "none";
            }
        });

        if (mensagemVazia) {
            mensagemVazia.style.display = (encontrados === 0) ? "block" : "none";
        }
    }

    // --- 3. INTERAÇÕES DE CLIQUE ---

    // Menu
    linksMenu.forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault(); 
            alert(`🐾 Você clicou em: ${link.textContent}. Essa seção será implementada em breve!`);
        });
    });

    // Botão "Ver Detalhes" com Verificação
    botoesDetalhes.forEach((botao) => {
        botao.addEventListener('click', (event) => {
            event.preventDefault();
            
            const card = botao.closest('.animal-card');
            if (!card || !modalConteudo || !modal) return;

            const nomeAnimal = card.querySelector('h3')?.textContent || "Animal";
            const infos = card.querySelector('p:nth-of-type(1)')?.textContent || "";
            const local = card.querySelector('p:nth-of-type(2)')?.textContent || "";

            botao.textContent = "Carregando...";
            botao.style.backgroundColor = "#a29bfe";
            botao.style.color = "white";

            setTimeout(() => {
                modalConteudo.innerHTML = `
                    <h2 style="color:#6c5ce7; margin-bottom:15px;">${nomeAnimal}</h2>
                    <p style="color:#64748b; font-size:1.1rem; margin-bottom:10px;">${infos}</p>
                    <p style="font-weight:bold; color:#2d3436;">📍 ${local}</p>
                    <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                    <p style="font-size: 0.9rem; color: #6c5ce7;">Deseja adotar este pet? Entre em contato conosco!</p>
                `;
                modal.style.display = 'flex';
                botao.textContent = "Ver Detalhes";
                botao.style.backgroundColor = "transparent";
                botao.style.color = "#6c5ce7";
            }, 500);
        });
    });

    // --- 4. EFEITOS VISUAIS TECH ---
    cards.forEach((card) => {
        const imagem = card.querySelector('img');

        card.addEventListener('mouseenter', () => {
            card.style.backgroundColor = "#f0f0ff";
            card.style.cursor = "pointer";
            card.style.borderColor = "#6366f1"; 
            card.style.transform = "translateY(-10px) scale(1.02)";
            card.style.transition = "all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)";

            if (imagem) {
                imagem.style.filter = "brightness(1.1)";
                imagem.style.transition = "all 0.4s ease"; 
            }
        });

        card.addEventListener('mouseleave', () => {
            card.style.backgroundColor = "white";
            card.style.borderColor = "rgba(0,0,0,0.03)";
            card.style.transform = "translateY(0) scale(1)";
            if (imagem) imagem.style.filter = "brightness(1)";
        });
    });

    // --- 5. OUVINTES DE EVENTOS ---
    campoBusca?.addEventListener('input', filtrarAnimais);
    seletorEspecie?.addEventListener('change', filtrarAnimais);

    if (formFiltro) {
        formFiltro.addEventListener('submit', (e) => {
            e.preventDefault();
            filtrarAnimais();
        });
    }

    console.log("✅ Script AdotaPet v3.0 Corrigido e Carregado!");
};
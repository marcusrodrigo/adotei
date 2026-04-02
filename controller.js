/**
 * CONTROLLER.JS - Versão Final Unificada com UX do Caos
 */

// --- 1. SELETORES ---
const listaGrid = document.querySelector('#listaItens');
const formulario = document.querySelector('#meuFormulario');
const inputBusca = document.querySelector('#inputBusca');
const filtroEspecie = document.querySelector('#filtroEspecie');

// Seletores do Caos
const btnCaos = document.querySelector('#btn-caos');
const displayAno = document.querySelector('#display-ano');
const inputDataOculto = document.querySelector('#data');
const areaCaos = document.querySelector('#caos-area');

let anoAtual = 1900;

// --- LÓGICA DO CAOS: O BOTÃO FUJÃO ---
btnCaos.addEventListener('mouseover', () => {
    const maxX = areaCaos.clientWidth - btnCaos.offsetWidth;
    const maxY = areaCaos.clientHeight - btnCaos.offsetHeight;
    
    const newX = Math.random() * maxX;
    const newY = Math.random() * maxY;
    
    btnCaos.style.left = `${newX}px`;
    btnCaos.style.top = `${newY}px`;
});

btnCaos.addEventListener('click', () => {
    anoAtual++;
    if(anoAtual > 2026) anoAtual = 1900; // Reseta se passar do limite
    displayAno.innerText = anoAtual;
    inputDataOculto.value = `${anoAtual}-01-01`; // Atualiza o valor para o banco
});

// --- 2. DADOS ORIGINAIS ---
const petsOriginais = [
    { 
        id: 'fixo1', 
        nome: 'Rex', 
        categoria: 'Cão', 
        data: '2024-01-15', 
        porte: 'Médio', 
        idade: '2 anos', 
        local: 'São Paulo - SP', 
        foto: 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=400&q=80' 
    },
    { 
        id: 'fixo2', 
        nome: 'Mimi', 
        categoria: 'Gata', 
        data: '2024-02-10', 
        porte: 'Pequeno', 
        idade: '1 ano', 
        local: 'Rio de Janeiro - RJ', 
        foto: 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=400&q=80' 
    }
];

// --- 3. RENDERIZAÇÃO E FILTRO ---
async function atualizarLista() {
    try {
        const itensBanco = await buscarItens(); 
        const todosOsPets = [...petsOriginais, ...itensBanco];
        
        const nomeBuscado = inputBusca.value.toLowerCase();
        const especieSelecionada = filtroEspecie.value;

        listaGrid.innerHTML = '';

        const petsFiltrados = todosOsPets.filter(pet => {
            const condicaoNome = pet.nome.toLowerCase().includes(nomeBuscado);
            const condicaoEspecie = (especieSelecionada === "Todos") || (pet.categoria === especieSelecionada);
            return condicaoNome && condicaoEspecie;
        });

        if (petsFiltrados.length === 0) {
            listaGrid.innerHTML = `<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #636e72;"><p>🐾 Nenhum amiguinho encontrado.</p></div>`;
            return;
        }

        petsFiltrados.forEach(pet => {
            const card = document.createElement('article');
            card.className = 'animal-card';
            
            const dataExibicao = pet.data.split('-').reverse().join('/');
            const fotoUrl = pet.foto || (pet.categoria === 'Gato' 
                ? 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=400&q=80' 
                : 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=400&q=80');

            card.innerHTML = `
                <div class="badge">${typeof pet.id === 'string' ? 'Destaque' : 'Novo'}</div>
                <img src="${fotoUrl}" alt="Foto de ${pet.nome}">
                <div class="card-content">
                    <h3>${pet.nome} (${pet.categoria})</h3>
                    <p><strong>Resgate/Nasc:</strong> ${dataExibicao}</p>
                    <p>📍 ${pet.local || 'Disponível'}</p>
                    
                    <button onclick="verDetalhes('${pet.nome}', '${pet.categoria}', '${dataExibicao}')" class="btn-secondary">Ver Detalhes</button>
                    
                    ${typeof pet.id === 'number' ? `
                        <button onclick="removerPet(${pet.id})" style="background:#ff7675; color:white; border:none; padding:10px; border-radius:12px; margin-top:10px; width:100%; cursor:pointer; font-weight:600;">Remover</button>
                    ` : ''}
                </div>
            `;
            listaGrid.appendChild(card);
        });
    } catch (e) { console.error(e); }
}

// --- 4. HANDLERS DE EVENTOS ---

inputBusca.addEventListener('input', atualizarLista);
filtroEspecie.addEventListener('change', atualizarLista);

window.resetarFiltros = () => {
    inputBusca.value = '';
    filtroEspecie.value = 'Todos';
    atualizarLista();
};

formulario.addEventListener('submit', async (e) => {
    e.preventDefault();
    const novoPet = {
        nome: document.querySelector('#nome').value,
        categoria: document.querySelector('#categoria').value,
        data: document.querySelector('#data').value,
        local: 'Aguardando lar',
        idade: 'N/A'
    };
    await adicionarItem(novoPet);
    
    // Reseta o formulário e o Caos
    formulario.reset();
    anoAtual = 1900;
    displayAno.innerText = "1900";
    inputDataOculto.value = "1900-01-01";
    
    atualizarLista();
});

window.removerPet = async (id) => {
    if(confirm("Deseja remover este registro?")) {
        await deletarItem(id);
        atualizarLista();
    }
};

// --- MODAL COM MENSAGEM RESTAURADA ---
window.verDetalhes = (nome, especie, data) => {
    const modal = document.getElementById('modal-pet');
    const conteudo = document.getElementById('modal-conteudo');
    conteudo.innerHTML = `
        <h2 style="color:var(--primary); margin-bottom:15px;">🐾 Conheça o(a) ${nome}</h2>
        <p>Este amiguinho é um <strong>${especie}</strong>.</p>
        <p style="margin: 15px 0;">Registrado em nosso sistema com a data de resgate ou nascimento em: <strong>${data}</strong>.</p>
        <p>Vamos dar um lar para ele?</p>
    `;
    modal.style.display = 'flex';
};

// Efeito de flash no menu
document.querySelectorAll('header a').forEach(link => {
    link.addEventListener('click', function() {
        const href = this.getAttribute('href');
        if (href && href.startsWith('#')) {
            const secao = document.querySelector(href);
            if (secao) {
                secao.classList.add('secao-foco');
                setTimeout(() => secao.classList.remove('secao-foco'), 1500);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', () => atualizarLista());
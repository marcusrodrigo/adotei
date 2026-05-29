/**
 * CONTROLLER.JS - VERSÃO COMPLETA E INTEGRADA
 */

// --- SEGURANÇA: Escape de HTML para prevenir XSS ---
function escapeHTML(str) {
    if (str == null) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

const MAX_UPLOAD_SIZE = 15 * 1024 * 1024; // 15 MB

// --- BANCO DE DADOS LOCAL (LocalStorage) ---
const buscarItens = () => JSON.parse(localStorage.getItem('adota_pet_master_db')) || [];

const adicionarItem = (pet) => {
    const pets = buscarItens();
    pet.id = Date.now(); // ID numérico para permitir exclusão
    pets.push(pet);
    localStorage.setItem('adota_pet_master_db', JSON.stringify(pets));
};

const deletarItem = (id) => {
    const pets = buscarItens().filter(p => p.id !== id);
    localStorage.setItem('adota_pet_master_db', JSON.stringify(pets));
};

// --- SELETORES ---
const listaGrid = document.querySelector('#listaItens');
const formulario = document.querySelector('#meuFormulario');
const inputBusca = document.querySelector('#inputBusca');
const filtroEspecie = document.querySelector('#filtroEspecie');

// --- DARK MODE ---
document.getElementById('btn-dark-toggle').addEventListener('click', () => {
    document.body.classList.toggle('dark-theme');
});

// --- REX E MIMI (DADOS FIXOS) ---
const petsFicticios = [
    {
        id: 'fixo_1', nome: 'Rex', categoria: 'Cão', data: '10/03/2024',
        descricao: 'Golden Retriever dócil, vacinado e muito companheiro.',
        foto: 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=400&q=80'
    },
    {
        id: 'fixo_2', nome: 'Mimi', categoria: 'Gato', data: '05/04/2024',
        descricao: 'Gatinha calma que adora um colo e é muito limpinha.',
        foto: 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=400&q=80'
    }
];

// --- FUNÇÃO DE RENDERIZAÇÃO ---
function atualizarLista() {
    const salvosNoBanco = buscarItens();
    const todosOsAnimais = [...petsFicticios, ...salvosNoBanco];

    const termoPesquisa = inputBusca.value.toLowerCase();
    const especieSelecionada = filtroEspecie.value;

    listaGrid.innerHTML = '';

    // Filtragem
    const resultadoFiltrado = todosOsAnimais.filter(pet => {
        const condicaoEspecie = (especieSelecionada === "Todos" || pet.categoria === especieSelecionada);
        const condicaoNome = pet.nome.toLowerCase().includes(termoPesquisa);
        return condicaoEspecie && condicaoNome;
    });

    // MENSAGEM DE ERRO: Nenhum amiguinho encontrado
    if (resultadoFiltrado.length === 0) {
        listaGrid.innerHTML = `<div class="mensagem-vazia">nenhum amiguinho encontrado 🐾</div>`;
        return;
    }

    // Criar os Cards
    resultadoFiltrado.forEach(pet => {
        const card = document.createElement('div');
        card.className = 'animal-card';

        const safeNome = escapeHTML(pet.nome);
        const safeCategoria = escapeHTML(pet.categoria);
        const safeData = escapeHTML(pet.data);
        const safeFoto = escapeHTML(pet.foto || 'https://via.placeholder.com/400x450?text=Animal+Sem+Foto');
        const safeDescricao = escapeHTML(pet.descricao || '');

        card.innerHTML = `
            <img src="${safeFoto}" alt="${safeNome}">
            <div class="card-content">
                <h3>${safeNome}</h3>
                <p><strong>Espécie:</strong> ${safeCategoria}</p>
                <p><strong>Data de Cadastro:</strong> ${safeData}</p>
                <button class="btn-secondary btn-detalhes" style="margin-top:20px">Ver Detalhes</button>
                ${typeof pet.id === 'number' ? `<button class="btn-remover">Excluir Registro</button>` : ''}
            </div>
        `;

        // Event listeners seguros em vez de inline onclick
        const btnDetalhes = card.querySelector('.btn-detalhes');
        if (btnDetalhes) {
            btnDetalhes.addEventListener('click', () => {
                verDetalhes(pet.nome, pet.categoria, pet.data, pet.descricao || '');
            });
        }
        const btnRemover = card.querySelector('.btn-remover');
        if (btnRemover) {
            btnRemover.addEventListener('click', () => {
                removerPet(pet.id);
            });
        }

        listaGrid.appendChild(card);
    });
}

// --- LOGICA DE CADASTRO ---
formulario.addEventListener('submit', (e) => {
    e.preventDefault();
    const inputFoto = document.querySelector('#foto').files[0];

    const finalizarSalvamento = (base64String) => {
        const novoPet = {
            nome: document.querySelector('#nome').value,
            categoria: document.querySelector('#categoria').value,
            descricao: document.querySelector('#custom-msg').value,
            data: new Date().toLocaleDateString('pt-BR'),
            foto: base64String
        };
        adicionarItem(novoPet);
        formulario.reset();
        atualizarLista();
        // Rola a tela suavemente para a galeria
        document.querySelector('#adotar-ancora').scrollIntoView();
    };

    if (inputFoto) {
        if (inputFoto.size > MAX_UPLOAD_SIZE) {
            alert('O arquivo excede o limite de 15 MB. Por favor, escolha um arquivo menor.');
            return;
        }
        const reader = new FileReader();
        reader.onloadend = () => finalizarSalvamento(reader.result);
        reader.readAsDataURL(inputFoto);
    } else {
        finalizarSalvamento(null);
    }
});

// --- MODAL DE DETALHES ---
window.verDetalhes = (nome, especie, data, msg) => {
    const modal = document.getElementById('modal-pet');
    const conteudo = document.getElementById('modal-conteudo');

    const safeNome = escapeHTML(nome);
    const safeEspecie = escapeHTML(especie);
    const safeData = escapeHTML(data);
    const safeMsg = escapeHTML(msg);

    const textoPadrao = `Este é o(a) ${safeNome}, um(a) ${safeEspecie} muito especial registrado em ${safeData}. Que tal dar um novo lar para este amiguinho?`;

    conteudo.innerHTML = `
        <h2 style="color:var(--accent); margin-bottom:25px;">🐾 ${safeNome}</h2>
        <p style="font-size: 1.2rem; line-height: 1.8;">${safeMsg.trim() !== "" ? safeMsg : textoPadrao}</p>
    `;
    modal.style.display = 'flex';
};

// --- REMOÇÃO E FILTROS ---
window.removerPet = (id) => {
    if(confirm("Tem certeza que deseja excluir este pet permanentemente do banco?")) {
        deletarItem(id);
        atualizarLista();
    }
};

const formBusca = document.querySelector('#formBusca');
if (formBusca) {
    formBusca.addEventListener('submit', (e) => e.preventDefault());
}

const btnLimparFiltros = document.getElementById('btn-limpar-filtros');
if (btnLimparFiltros) {
    btnLimparFiltros.addEventListener('click', () => {
        inputBusca.value = '';
        filtroEspecie.value = 'Todos';
        atualizarLista();
    });
}

window.resetarFiltros = () => {
    inputBusca.value = '';
    filtroEspecie.value = 'Todos';
    atualizarLista();
};

inputBusca.addEventListener('input', atualizarLista);
filtroEspecie.addEventListener('change', atualizarLista);

// --- FECHAR MODAL (sem inline onclick) ---
const btnFecharModal = document.getElementById('btn-fechar-modal');
if (btnFecharModal) {
    btnFecharModal.addEventListener('click', () => {
        document.getElementById('modal-pet').style.display = 'none';
    });
}

// Inicializar lista ao carregar
atualizarLista();

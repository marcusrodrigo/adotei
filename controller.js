/**
 * CONTROLLER.JS - VERSÃO COMPLETA E INTEGRADA
 */

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
        card.innerHTML = `
            <img src="${pet.foto || 'https://via.placeholder.com/400x450?text=Animal+Sem+Foto'}" alt="${pet.nome}">
            <div class="card-content">
                <h3>${pet.nome}</h3>
                <p><strong>Espécie:</strong> ${pet.categoria}</p>
                <p><strong>Data de Cadastro:</strong> ${pet.data}</p>
                <button class="btn-secondary" style="margin-top:20px" onclick="verDetalhes('${pet.nome}', '${pet.categoria}', '${pet.data}', '${pet.descricao || ''}')">Ver Detalhes</button>
                ${typeof pet.id === 'number' ? `<button class="btn-remover" onclick="removerPet(${pet.id})">Excluir Registro</button>` : ''}
            </div>
        `;
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
    const textoPadrao = `Este é o(a) ${nome}, um(a) ${especie} muito especial registrado em ${data}. Que tal dar um novo lar para este amiguinho?`;

    conteudo.innerHTML = `
        <h2 style="color:var(--accent); margin-bottom:25px;">🐾 ${nome}</h2>
        <p style="font-size: 1.2rem; line-height: 1.8;">${msg.trim() !== "" ? msg : textoPadrao}</p>
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

window.resetarFiltros = () => {
    inputBusca.value = '';
    filtroEspecie.value = 'Todos';
    atualizarLista();
};

inputBusca.addEventListener('input', atualizarLista);
filtroEspecie.addEventListener('change', atualizarLista);

// Inicializar lista ao carregar
atualizarLista();

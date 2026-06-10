/**
 * CONTROLLER.JS — VERSÃO MVC (integrado com PHP/SQLite)
 * Os pets do banco chegam via window.petsDoBanco (injetado pelo view.php).
 * O LocalStorage é mantido apenas para compatibilidade futura; o banco
 * de verdade é o SQLite gerenciado pelo back-end.
 */

// --- SELETORES ---
const listaGrid     = document.querySelector('#listaItens');
const formulario    = document.querySelector('#meuFormulario');
const inputBusca    = document.querySelector('#inputBusca');
const filtroEspecie = document.querySelector('#filtroEspecie');

// --- DARK MODE ---
document.getElementById('btn-dark-toggle').addEventListener('click', () => {
    document.body.classList.toggle('dark-theme');
});

// --- PETS FIXOS (Rex e Mimi) ---
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

// --- PETS DO BANCO (injetados pelo PHP no view.php) ---
const petsDoBanco = window.petsDoBanco || [];

// --- FUNÇÃO DE RENDERIZAÇÃO ---
function atualizarLista() {
    // Mescla: fixos + banco (sem duplicar pelos ids)
    const todosOsAnimais = [...petsFicticios, ...petsDoBanco];

    const termoPesquisa      = inputBusca ? inputBusca.value.toLowerCase() : '';
    const especieSelecionada = filtroEspecie ? filtroEspecie.value : 'Todos';

    listaGrid.innerHTML = '';

    const resultadoFiltrado = todosOsAnimais.filter(pet => {
        const condicaoEspecie = (especieSelecionada === 'Todos' || pet.categoria === especieSelecionada);
        const condicaoNome    = pet.nome.toLowerCase().includes(termoPesquisa);
        return condicaoEspecie && condicaoNome;
    });

    if (resultadoFiltrado.length === 0) {
        listaGrid.innerHTML = `<div class="mensagem-vazia">nenhum amiguinho encontrado 🐾</div>`;
        return;
    }

    resultadoFiltrado.forEach(pet => {
        const card = document.createElement('div');
        card.className = 'animal-card';

        // Botão de exclusão só para pets do banco (id numérico)
        const btnExcluir = (typeof pet.id === 'number')
            ? `<form method="POST" action="index.php" style="display:inline">
                   <input type="hidden" name="_route" value="delete">
                   <input type="hidden" name="id" value="${pet.id}">
                   <button type="submit" class="btn-remover"
                       onclick="return confirm('Excluir permanentemente?')">
                       Excluir Registro
                   </button>
               </form>`
            : '';

        card.innerHTML = `
            <img src="${pet.foto || 'https://via.placeholder.com/400x450?text=Sem+Foto'}" alt="${pet.nome}">
            <div class="card-content">
                <h3>${pet.nome}</h3>
                <p><strong>Espécie:</strong> ${pet.categoria}</p>
                <p><strong>Data de Cadastro:</strong> ${pet.data}</p>
                <button class="btn-secondary" style="margin-top:20px"
                    onclick="verDetalhes('${pet.nome}', '${pet.categoria}', '${pet.data}', '${(pet.descricao || '').replace(/'/g, "\\'")}')">
                    Ver Detalhes
                </button>
                ${btnExcluir}
            </div>
        `;
        listaGrid.appendChild(card);
    });
}

// --- CONVERSÃO DE FOTO PARA BASE64 ANTES DO SUBMIT ---
// O formulário agora é um POST nativo para o PHP.
// A foto é lida pelo FileReader e jogada num hidden input antes do envio.
formulario.addEventListener('submit', (e) => {
    const inputFoto   = document.querySelector('#foto').files[0];
    const hiddenFoto  = document.querySelector('#foto-base64');

    if (inputFoto) {
        // Impede o submit imediato até o FileReader terminar
        e.preventDefault();
        const reader = new FileReader();
        reader.onloadend = () => {
            hiddenFoto.value = reader.result; // base64 data-URL
            formulario.submit();              // envia de verdade
        };
        reader.readAsDataURL(inputFoto);
    }
    // Se não há foto, o formulário submete normalmente sem preventDefault
});

// --- MODAL DE DETALHES ---
window.verDetalhes = (nome, especie, data, msg) => {
    const modal    = document.getElementById('modal-pet');
    const conteudo = document.getElementById('modal-conteudo');
    const textoPadrao = `Este é o(a) ${nome}, um(a) ${especie} muito especial registrado em ${data}. Que tal dar um novo lar para este amiguinho?`;

    conteudo.innerHTML = `
        <h2 style="color:var(--accent); margin-bottom:25px;">🐾 ${nome}</h2>
        <p style="font-size: 1.2rem; line-height: 1.8;">${msg.trim() !== '' ? msg : textoPadrao}</p>
    `;
    modal.style.display = 'flex';
};

// --- FILTROS ---
window.resetarFiltros = () => {
    if (inputBusca)    inputBusca.value    = '';
    if (filtroEspecie) filtroEspecie.value = 'Todos';
    atualizarLista();
};

inputBusca    && inputBusca.addEventListener('input',  atualizarLista);
filtroEspecie && filtroEspecie.addEventListener('change', atualizarLista);

// Renderiza ao carregar
atualizarLista();

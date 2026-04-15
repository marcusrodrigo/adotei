/**
 * CONTROLLER.JS - LOGICA COMPLETA
 * Gerencia a renderização, filtros, upload de imagem e data automática.
 */

const listaGrid = document.querySelector('#listaItens');
const formulario = document.querySelector('#meuFormulario');
const inputBusca = document.querySelector('#inputBusca');
const filtroEspecie = document.querySelector('#filtroEspecie');

// --- CONTROLE DARK MODE ---
const btnDark = document.getElementById('btn-dark-toggle');
btnDark.addEventListener('click', () => {
    document.body.classList.toggle('dark-theme');
    btnDark.innerText = document.body.classList.contains('dark-theme') ? '☀️ Light Mode' : '🌙 Dark Mode';
});

// --- DADOS ORIGINAIS CORRIGIDOS ---
const petsFicticios = [
    { 
        id: 'f1', nome: 'Rex', categoria: 'Cão', data: '2024-03-10', 
        foto: 'IMG_2004.jpg', mensagem: "" 
    },
    { 
        id: 'f2', nome: 'Mimi', categoria: 'Gato', data: '2024-04-15', 
        foto: 'IMG_2008.jpg', mensagem: ""
    }
];

// --- FUNÇÃO DE RENDERIZAÇÃO ---
async function atualizarLista() {
    try {
        const itensDB = await buscarItens(); // Função vinda do db.js
        const todosOsPets = [...petsFicticios, ...itensDB];
        
        const busca = inputBusca.value.toLowerCase();
        const especie = filtroEspecie.value;

        listaGrid.innerHTML = '';

        const filtrados = todosOsPets.filter(pet => {
            const matchNome = pet.nome.toLowerCase().includes(busca);
            const matchEspecie = (especie === "Todos") || (pet.categoria === especie);
            return matchNome && matchEspecie;
        });

        // Caso a busca esteja vazia
        if (filtrados.length === 0) {
            listaGrid.innerHTML = `
                <div class="empty-results">
                    <h2>🐾 Ninguém <br>Por Aqui <br>Ainda.</h2>
                    <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExM2Zic3B0Ynl0NXh4Ym4yc3R4M3V4M3V4M3V4M3V4M3V4M3V4M3V4JmVwPXYxX2ludGVybmFsX2dpZl9ieV9pZContext&rid=giphy.gif" alt="Nada encontrado">
                </div>`;
            return;
        }

        // Criar os Cards
        filtrados.forEach(pet => {
            const card = document.createElement('article');
            card.className = 'animal-card';
            
            const dataExibicao = pet.data.split('-').reverse().join('/');
            const fotoUrl = pet.foto || 'https://via.placeholder.com/800x1000?text=Sem+Foto';

            card.innerHTML = `
                <div class="badge">${typeof pet.id === 'string' ? 'Destaque' : 'Novo'}</div>
                <img src="${fotoUrl}" alt="Foto de ${pet.nome}">
                <div class="card-content">
                    <span class="tag-especie">${pet.categoria}</span>
                    <h3>${pet.nome}</h3>
                    <p>Cadastrado em: ${dataExibicao}</p>
                    
                    <button onclick="verDetalhes('${pet.nome}', '${pet.categoria}', '${dataExibicao}', '${pet.mensagem || ''}')" class="btn-secondary">Ver Detalhes</button>
                    
                    ${typeof pet.id === 'number' ? `
                        <button onclick="removerPet(${pet.id})" class="btn-remover">Remover do Banco</button>
                    ` : ''}
                </div>
            `;
            listaGrid.appendChild(card);
        });
    } catch (e) { console.error("Erro ao atualizar lista:", e); }
}

// --- VALIDACAO CUSTOMIZADA: TIPO E TAMANHO DE ARQUIVO ---
window.Parsley.addValidator('filesize', {
    validateString: function(_value, maxSizeKb, parsleyInstance) {
        var files = parsleyInstance.$element[0].files;
        if (files.length === 0) return true;
        return files[0].size <= maxSizeKb * 1024;
    },
    requirementType: 'integer',
    messages: {
        'pt-br': 'O arquivo deve ter no m\u00e1ximo %s KB.',
        en: 'File must be less than %s KB.'
    }
});

window.Parsley.addValidator('filetype', {
    validateString: function(_value, allowedTypes, parsleyInstance) {
        var files = parsleyInstance.$element[0].files;
        if (files.length === 0) return true;
        var allowedList = allowedTypes.split(',').map(function(t) { return t.trim().toLowerCase(); });
        var fileName = files[0].name.toLowerCase();
        var ext = '.' + fileName.split('.').pop();
        return allowedList.indexOf(ext) !== -1;
    },
    requirementType: 'string',
    messages: {
        'pt-br': 'Tipo de arquivo inv\u00e1lido. Tipos permitidos: %s',
        en: 'Invalid file type. Allowed types: %s'
    }
});

// --- INICIALIZAR PARSLEY NO FORMULARIO DE CADASTRO ---
var $formCadastro = $('#meuFormulario').parsley();

// Adicionar atributos de validacao de arquivo via JS (Parsley custom validators)
$('#foto-upload').attr('data-parsley-filesize', '15360');
$('#foto-upload').attr('data-parsley-filetype', '.jpg,.jpeg,.png');
$('#foto-upload').attr('data-parsley-filetype-message', 'Envie apenas imagens JPG ou PNG.');
$('#foto-upload').attr('data-parsley-filesize-message', 'A foto deve ter no m\u00e1ximo 15MB.');

// --- CADASTRO DE NOVO PET (DATA AUTOM\u00c1TICA + UPLOAD + VALIDA\u00c7\u00c3O) ---
formulario.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Validar com Parsley antes de prosseguir
    if (!$formCadastro.isValid()) {
        $formCadastro.validate();
        return;
    }

    const inputFoto = document.querySelector('#foto-upload');
    const arquivo = inputFoto.files[0];
    const msgUsuario = document.querySelector('#custom-msg').value;

    const processarEnvio = async (fotoBase64) => {
        const hoje = new Date().toISOString().split('T')[0]; // Captura data atual autom\u00e1tica
        
        const novoPet = {
            nome: document.querySelector('#nome').value,
            categoria: document.querySelector('#categoria').value,
            data: hoje,
            local: 'Aguardando lar',
            foto: fotoBase64,
            mensagem: msgUsuario
        };
        
        await adicionarItem(novoPet); // Fun\u00e7\u00e3o vinda do db.js
        formulario.reset();
        // Resetar estado de validacao apos envio bem-sucedido
        $formCadastro.reset();
        atualizarLista();
        // Feedback visual: rolar para a galeria
        document.querySelector('#adotar-ancora').scrollIntoView();
    };

    if (arquivo) {
        const leitor = new FileReader();
        leitor.onloadend = () => processarEnvio(leitor.result);
        leitor.readAsDataURL(arquivo);
    } else {
        processarEnvio(null);
    }
});

// --- MODAL COM MENSAGEM DINÂMICA ---
window.verDetalhes = (nome, especie, data, mensagem) => {
    const modal = document.getElementById('modal-pet');
    const conteudo = document.getElementById('modal-conteudo');
    
    // Texto padrão caso o usuário não digite nada
    const padrao = `Conheça o(a) ${nome}. Este amiguinho é um ${especie}. Registrado em nosso sistema com a data de cadastramento em: ${data}. Vamos dar um lar para ele?`;
    
    const textoFinal = mensagem.trim() !== "" ? mensagem : padrao;

    conteudo.innerHTML = `
        <h2>🐾 ${nome}</h2>
        <p>${textoFinal}</p>
    `;
    modal.style.display = 'flex';
};

// --- FUNÇÕES DE FILTRO ---
window.resetarFiltros = () => {
    inputBusca.value = '';
    filtroEspecie.value = 'Todos';
    atualizarLista();
};

window.removerPet = async (id) => {
    if(confirm("Deseja mesmo excluir este amiguinho do banco de dados?")) {
        await deletarItem(id); // Função vinda do db.js
        atualizarLista();
    }
};

inputBusca.addEventListener('input', atualizarLista);
filtroEspecie.addEventListener('change', atualizarLista);

// Inicialização
document.addEventListener('DOMContentLoaded', atualizarLista);

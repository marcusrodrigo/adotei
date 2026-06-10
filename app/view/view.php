<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdotaPet | Design &amp; Adoção</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800;900&display=swap" rel="stylesheet">
    <!-- CSS servido como arquivo estático pela pasta view -->
    <link rel="stylesheet" href="view/style.css">
</head>
<body>

<header>
    <div class="logo">
        <h1>🐾 Adota<span>Pet</span></h1>
    </div>
    <nav>
        <ul>
            <li><a href="#inicio">Início</a></li>
            <li><a href="#cadastro-ancora">Cadastrar</a></li>
            <li><a href="#adotar-ancora">Adotar</a></li>
            <li><button id="btn-dark-toggle">🌙 Dark Mode</button></li>
        </ul>
    </nav>
</header>

<section class="hero" id="inicio">
    <div class="hero-content">
        <h2>Amor em cada patinha.</h2>
        <p>Encontre seu novo melhor amigo no AdotaPet. Design moderno para uma causa nobre.</p>
    </div>
    <div class="hero-container-images">
        <img src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=1200&q=80" alt="Hero Image">
    </div>
</section>

<main class="container-full">

    <?php if (!empty($resposta)): ?>
    <div style="
        padding: 20px 5%;
        margin-bottom: 0;
        background-color: <?= $resposta['sucesso'] ? '#d4edda' : '#f8d7da' ?>;
        color: #333;
        font-weight: 600;
        border-left: 6px solid <?= $resposta['sucesso'] ? '#28a745' : '#dc3545' ?>;
    ">
        <?= htmlspecialchars($resposta['msg'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php endif; ?>

    <!-- FORMULÁRIO DE CADASTRO -->
    <section class="section-block" id="cadastro-ancora">
        <h3>Cadastrar Novo Pet</h3>
        <!--
            O formulário envia para index.php (POST /).
            A foto é convertida para base64 pelo controller.js antes do submit,
            então usamos um hidden input para transportá-la.
        -->
        <form id="meuFormulario" method="POST" action="index.php">
            <div class="form-grid-extra">
                <div class="field">
                    <label for="nome">Nome do Pet</label>
                    <input type="text" id="nome" name="nome" placeholder="Ex: Rex" required>
                </div>
                <div class="field">
                    <label for="categoria">Espécie</label>
                    <select id="categoria" name="categoria" required>
                        <option value="Cão">Cão</option>
                        <option value="Gato">Gato</option>
                    </select>
                </div>
                <div class="field">
                    <label for="foto">Foto do Pet</label>
                    <input type="file" id="foto" accept="image/*">
                    <!-- Input oculto que receberá o base64 da foto via JS -->
                    <input type="hidden" id="foto-base64" name="foto">
                </div>
            </div>
            <div class="field">
                <label for="custom-msg">Mensagem Personalizada (Opcional)</label>
                <textarea id="custom-msg" name="custom-msg" rows="4"
                    placeholder="Conte a história deste pet ou algo que chame atenção para a adoção..."></textarea>
            </div>
            <button type="submit" class="btn-primary">Salvar no Banco de Dados</button>
        </form>
    </section>

    <hr class="divider-long">

    <!-- FILTROS -->
    <section class="section-block" id="adotar-ancora">
        <h3>Filtrar Galeria</h3>
        <form id="formBusca" onsubmit="event.preventDefault();">
            <div class="form-grid-extra">
                <div class="field">
                    <label for="inputBusca">Pesquisar por Nome</label>
                    <input type="search" id="inputBusca" placeholder="Quem você está procurando? (Ex: Rex, Mimi...)">
                </div>
                <div class="field">
                    <label for="filtroEspecie">Filtrar por Espécie</label>
                    <select id="filtroEspecie">
                        <option value="Todos">Todos os Animais</option>
                        <option value="Cão">Apenas Cães</option>
                        <option value="Gato">Apenas Gatos</option>
                    </select>
                </div>
                <div class="field btn-align">
                    <button type="button" class="btn-secondary" onclick="resetarFiltros()">Limpar Filtros</button>
                </div>
            </div>
        </form>
    </section>

    <!-- GALERIA -->
    <section class="section-block">
        <div class="animal-grid" id="listaItens"></div>
    </section>

</main>

<!-- MODAL DE DETALHES -->
<div id="modal-pet">
    <div class="modal-body">
        <div id="modal-conteudo"></div>
        <button onclick="document.getElementById('modal-pet').style.display='none'" class="btn-primary">
            Fechar Detalhes
        </button>
    </div>
</div>

<!--
    Injeta os pets do banco como variável JS global.
    O controller.js os mescla com os pets fixos (Rex e Mimi)
    e com o LocalStorage — sem quebrar nenhuma lógica existente.
-->
<script>
    window.petsDoBanco = <?php
        $payload = array_map(function($r) {
            return [
                'id'        => $r->id,
                'nome'      => $r->nome,
                'categoria' => $r->categoria,
                'descricao' => $r->descricao ?? '',
                'foto'      => $r->foto ?? '',
                'data'      => $r->data,
                'origem'    => 'banco', // distingue de LocalStorage
            ];
        }, $relatos ?? []);
        echo json_encode($payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
    ?>;
</script>

<script src="view/db.js"></script>
<script src="view/controller.js"></script>
<script src="view/script.js"></script>
</body>
</html>

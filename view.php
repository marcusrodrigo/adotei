<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Matrícula | AdotaPet Acadêmico</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo"><h1>🐾 Adota<span>Acadêmico</span></h1></div>
    </header>

    <main class="container-full">
        <section class="section-block">
            <h3>Ficha de Matrícula</h3>
            
            <?php if (isset($resposta)): ?>
                <div style="padding: 20px; margin-bottom: 20px; background: <?= $resposta['sucesso'] ? '#d4edda' : '#f8d7da' ?>; color: #333;">
                    <?= $resposta['msg'] ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php">
                <div class="form-grid-extra">
                    <div class="field">
                        <label>Nome Completo</label>
                        <input type="text" name="nome" placeholder="Ex: João Silva">
                    </div>
                    <div class="field">
                        <label>Idade</label>
                        <input type="number" name="idade" placeholder="Ex: 20">
                    </div>
                    <div class="field">
                        <label>Curso Desejado</label>
                        <select name="curso">
                            <option value="Veterinária">Auxiliar de Veterinária</option>
                            <option value="Comportamento">Comportamento Animal</option>
                            <option value="Grooming">Grooming e Estética</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Finalizar Matrícula</button>
            </form>
        </section>
    </main>
</body>
</html>
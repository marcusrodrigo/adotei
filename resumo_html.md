# Resumo de Aprendizado: HTML5

## 1. O que é HTML?
O HTML (**HyperText Markup Language**) não é uma linguagem de programação, mas sim uma **linguagem de marcação**. 
* **Diferença fundamental:** Enquanto linguagens de programação lidam com lógica e processamento de dados, o HTML lida com a **estruturação** e a **semântica** do conteúdo em uma página web.

## 2. Anatomia de uma Tag
Uma tag padrão é composta por:
1. **Abertura:** `<tag>`
2. **Conteúdo:** O texto ou elemento interno.
3. **Fechamento:** `</tag>` (notar a barra inclinada).



## 3. Estrutura Básica Obrigatória
Todo documento HTML deve seguir esta hierarquia para ser interpretado corretamente pelos navegadores:
- `<!DOCTYPE html>`: Define que o arquivo é HTML5.
- `<html>`: O elemento raiz que envolve todo o conteúdo.
- `<head>`: Contém metadados, títulos da aba e links para estilos (CSS).
- `<body>`: Onde fica toda a parte visual e o conteúdo que o usuário vê.

## 4. Glossário de Tags Principais
| Tag | Descrição |
| :--- | :--- |
| `<h1>` a `<h6>` | Títulos e subtítulos (h1 é o mais importante). |
| `<p>` | Define um parágrafo de texto. |
| `<a>` | Cria links (utiliza o atributo `href`). |
| `<img>` | Insere imagens (utiliza os atributos `src` e `alt`). |
| `<ul>` / `<li>` | Cria listas não ordenadas (com marcadores). |

## 5. A Importância da Tag <div>
A `<div>` funciona como um **container genérico**. Ela é essencial para:
- **Agrupamento:** Unir vários elementos para aplicar estilos ou comportamentos de uma vez.
- **Organização/Aninhamento:** Facilitar a leitura do código e a criação de layouts (como colunas ou seções específicas).
- **Semântica (quando usada com IDs/Classes):** Ajuda a separar o cabeçalho, o corpo e o rodapé da página.

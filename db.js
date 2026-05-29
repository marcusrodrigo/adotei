/**
 * db.js - Camada de acesso a dados com Dexie.js
 * Gerencia o banco IndexedDB "AdotaPetDB" de forma robusta,
 * com promises nativas, versionamento e tratamento de erros.
 */

const db = new Dexie('AdotaPetDB');

// Definicao declarativa do schema (versao 1)
db.version(1).stores({
  pets: '++id, nome, categoria, data'
});

/**
 * Adiciona um novo pet ao banco de dados.
 * @param {Object} item - Dados do pet a ser cadastrado.
 * @returns {Promise<number>} ID gerado para o novo registro.
 */
async function adicionarItem(item) {
  try {
    const id = await db.pets.add(item);
    return id;
  } catch (error) {
    console.error('Erro ao salvar pet:', error);
    throw new Error('Nao foi possivel salvar o pet. Verifique os dados e tente novamente.');
  }
}

/**
 * Retorna todos os pets cadastrados no banco.
 * @returns {Promise<Array>} Lista de objetos pet.
 */
async function buscarItens() {
  try {
    const itens = await db.pets.toArray();
    return itens;
  } catch (error) {
    console.error('Erro ao buscar pets:', error);
    throw new Error('Nao foi possivel carregar os pets. Tente recarregar a pagina.');
  }
}

/**
 * Remove um pet do banco pelo seu ID.
 * @param {number} id - ID do pet a ser removido.
 * @returns {Promise<void>}
 */
async function deletarItem(id) {
  try {
    await db.pets.delete(id);
  } catch (error) {
    console.error('Erro ao deletar pet:', error);
    throw new Error('Nao foi possivel remover o pet. Tente novamente.');
  }
}

/**
 * Atualiza os dados de um pet existente.
 * @param {number} id - ID do pet a ser atualizado.
 * @param {Object} alteracoes - Campos a serem atualizados.
 * @returns {Promise<number>} Numero de registros atualizados (0 ou 1).
 */
async function atualizarItem(id, alteracoes) {
  try {
    const atualizado = await db.pets.update(id, alteracoes);
    if (atualizado === 0) {
      console.warn('Nenhum pet encontrado com o ID:', id);
    }
    return atualizado;
  } catch (error) {
    console.error('Erro ao atualizar pet:', error);
    throw new Error('Nao foi possivel atualizar o pet. Verifique os dados e tente novamente.');
  }
}

/**
 * Busca um unico pet pelo seu ID.
 * @param {number} id - ID do pet.
 * @returns {Promise<Object|undefined>} O objeto pet ou undefined se nao encontrado.
 */
async function buscarItemPorId(id) {
  try {
    const item = await db.pets.get(id);
    return item;
  } catch (error) {
    console.error('Erro ao buscar pet por ID:', error);
    throw new Error('Nao foi possivel buscar o pet. Tente novamente.');
  }
}

/**
 * Conta o total de pets cadastrados.
 * @returns {Promise<number>} Quantidade de registros.
 */
async function contarItens() {
  try {
    const total = await db.pets.count();
    return total;
  } catch (error) {
    console.error('Erro ao contar pets:', error);
    throw new Error('Nao foi possivel contar os pets.');
  }
}

/**
 * Remove todos os pets do banco de dados.
 * @returns {Promise<void>}
 */
async function limparBanco() {
  try {
    await db.pets.clear();
  } catch (error) {
    console.error('Erro ao limpar banco:', error);
    throw new Error('Nao foi possivel limpar o banco de dados.');
  }
}

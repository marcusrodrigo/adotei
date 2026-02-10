## Requisitos Funcionais (RF)

**RF01 – Cadastro de Animal Híbrido**  
O sistema deve permitir que usuários comuns e ONGs/protetores cadastrem animais para adoção.  
- Campos obrigatórios: Foto, Espécie, Porte, Idade Estimada, Localização e Descrição.  
- Anúncios de usuários não homologados devem exibir o selo **“Não Verificado”**.  
- Edições em campos críticos (espécie, porte) devem gerar log e notificar adotantes com interesse pendente.  

Critérios de aceite:  
- [ ] Validar presença de pelo menos uma imagem.  
- [ ] Exibir tag visual de verificação conforme o perfil do autor.  

---

**RF02 – Intenção de Adoção via Formulário**  
O sistema deve registrar a intenção de adoção por meio de um formulário estruturado.  
- Campos obrigatórios: Nome, e-mail e motivação.  
- O envio do formulário exige login/cadastro do adotante.  

Critérios de aceite:  
- [ ] Bloquear envio por usuários não logados.  
- [ ] Impedir duplicidade de interesse do mesmo adotante para o mesmo animal.  

---

**RF03 – Gestão de Status da Solicitação (Funil)**  
O protetor deve gerenciar as solicitações por meio de estados lógicos e controlados.  
- Status permitidos: Pendente (inicial), Em Análise, Aprovado, Rejeitado, Concluído.  
- Ao marcar como Concluído, o animal deve ser removido das buscas públicas.  

Critérios de aceite:  
- [ ] Validar transição linear entre estados.  

---

**RF04 – Cancelamento e Notificação Automática**  
O sistema deve automatizar o encerramento de solicitações secundárias após a adoção.  
- Ao marcar um animal como Concluído, todas as outras solicitações em Pendente ou Em Análise devem ser encerradas automaticamente.  
- O sistema deve enviar e-mail padrão aos adotantes impactados.  

Critérios de aceite:  
- [ ] Disparar e-mail automático em massa no gatilho de conclusão.  

---

**RF05 – Painel de Controle Priorizado do Protetor**  
O painel do protetor deve priorizar itens que exigem ação.  
- Prioridade 1: Animais com solicitações Pendentes (ordenados pela solicitação mais antiga).  
- Prioridade 2: Animais sem solicitações (ordenados pela data de cadastro).  

Critérios de aceite:  
- [ ] Ordenar dinamicamente a lista conforme status das solicitações vinculadas.  

---

**RF06 – Busca e Filtros Explícitos**  
O sistema deve disponibilizar catálogo aberto com filtros claros.  
- Filtros disponíveis: Espécie, Porte, Idade e Localização (Cidade/Estado).  
- A localização do navegador pode ser sugerida, mas nunca aplicada automaticamente.  

Critérios de aceite:  
- [ ] Permitir limpeza total dos filtros e busca ampla.  

---

## Regras de Negócio (RN)

**RN01 – Divulgação Progressiva de Dados**  
Dados sensíveis do adotante (ex: CPF, telefone) só devem ser visíveis ao protetor quando a solicitação estiver em **Em Análise** ou **Aprovado**.

**RN02 – Expiração de Anúncios**  
- Anúncios sem atualização por 30 dias devem gerar alerta ao protetor.  
- Após 60 dias sem ação, o anúncio deve ser marcado automaticamente como **Inativo**.

**RN03 – Unicidade de Adoção**  
Um animal pode ter apenas uma solicitação no status **Aprovado** por vez.

---

## Requisitos Não Funcionais (RNF)

**RNF01 – Segurança**  
Os dados pessoais devem ser armazenados com criptografia em repouso, em conformidade com a LGPD.

**RNF02 – Usabilidade**  
A interface deve ser responsiva e utilizável em navegadores de dispositivos móveis.

**RNF03 – Performance**  
A busca e listagem de animais devem ter tempo de resposta inferior a 2 segundos.

**RNF04 – Disponibilidade**  
O catálogo de animais deve ser acessível publicamente, 24/7, sem necessidade de autenticação.

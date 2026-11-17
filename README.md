# 🛡️ Verifica Loja

**Cole o link de um anúncio e descubra se a loja apresenta sinais de golpe — antes de comprar.**

> 🚧 **Status:** em desenvolvimento ativo (Etapa 1 — Fase 1). Este README descreve o que já funciona e, separadamente, o que está planejado. Veja o [roadmap](#️-roadmap).

---

## 💡 O problema

Minha mãe caiu em um golpe comprando em um site falso. O anúncio parecia real, o preço parecia uma boa promoção — e o produto nunca chegou.

Golpes assim atingem principalmente quem tem menos familiaridade com a internet. As informações para identificar um site suspeito existem (dados da empresa na Receita Federal, idade do domínio, listas do Procon), mas estão espalhadas e exigem conhecimento técnico para consultar.

**O Verifica Loja junta essas fontes em um só lugar e responde, em linguagem simples: "posso confiar nesta loja?"**

### Para quem é

Pessoas leigas e idosas — quem mais cai nesses golpes. Isso guia todas as decisões de interface e de texto: um único campo, nenhum termo técnico, cada risco explicado em uma frase.

---

## 🔍 Como funciona

A pessoa **só cola o link do anúncio**. O sistema faz o resto:

```
Link do anúncio
      │
      ├─▶ Extrai o domínio da loja
      ├─▶ Lê a página e procura o CNPJ e o preço
      ├─▶ Consulta fontes públicas (Receita Federal, Registro.br, Procon...)
      ├─▶ Cruza as informações e calcula o nível de risco
      │
      ▼
Resultado: risco BAIXO / MÉDIO / ALTO + explicação de cada motivo
```

### Sinais verificados

| Sinal | Por que importa | Fonte |
|---|---|---|
| CNPJ existe e está ativo | Lojas falsas inventam CNPJs ou usam o de outra empresa | BrasilAPI (Receita Federal) |
| Ramo da empresa é compatível | Uma padaria vendendo iPhone é um alerta forte | BrasilAPI (CNAE) |
| Idade do domínio | Sites de golpe costumam ter dias ou semanas de vida | RDAP / Registro.br |
| Lista de sites não recomendados | Lojas que já lesaram consumidores | Procon-SP |
| Listas de phishing | Sites já denunciados como golpe | Google Safe Browsing |
| Desconto fora da realidade | "70% off em lançamento" é o padrão clássico | Análise do preço |
| Domínio imitando marca famosa | Ex.: `loja-oficial-marca.shop` | Comparação com marcas conhecidas |

---

## 🤖 IA, agentes e automação (Etapa 2 — planejado)

Depois da base em Laravel, o projeto evolui para usar IA onde ela agrega — **sempre como apoio à verificação, nunca como juiz único**.

| Recurso | O que fará | Conceitos envolvidos |
|---|---|---|
| **API REST** | Permite que outros sistemas consultem o verificador | Rotas de API, tokens, rate limiting |
| **Bot de mensagens (n8n)** | O usuário envia o link pelo Telegram/WhatsApp e recebe a resposta na conversa, sem abrir site nenhum | Automação no-code, webhooks, integração entre sistemas |
| **Agente explicador** | Transforma o resultado técnico em uma explicação curta e acessível | LLM via API, prompts, saída estruturada |
| **Agente analisador** | Lê o texto do anúncio, identifica padrões de golpe (urgência artificial, promessas irreais, contato só por WhatsApp) e decide quais verificações acionar | Agentes com ferramentas (*tool use*) |
| **Evals** | Mede a taxa de acerto em um conjunto de sites conhecidos (golpe × legítimos) a cada mudança de prompt, modelo ou regra | Experimentos, métricas |
| **RAG** | Busca guias oficiais e denúncias semelhantes para embasar a explicação e citar a fonte | Embeddings, busca vetorial |
| **Servidor MCP** | Expõe `verificar_loja(url)` como ferramenta para assistentes de IA | Model Context Protocol |

**Princípios de uso da IA:**
- **Conferir antes de confiar:** a saída de um modelo é cruzada com dados objetivos antes de chegar ao usuário.
- **Explicar sempre:** o sistema mostra por que chegou a cada conclusão.
- **Medir, não achar:** toda mudança em prompt ou modelo passa pelos evals.
- **Agente só onde precisa interpretar:** verificações objetivas (o CNPJ está ativo?) continuam em código determinístico.

---

## ⚖️ Decisões de projeto

| Decisão | Por quê |
|---|---|
| **A entrada é o link, não o CNPJ** | A primeira ideia era pedir o CNPJ, mas a maioria das pessoas não sabe onde encontrá-lo. O sistema o descobre sozinho. |
| **"Sinais de risco", nunca "é golpe"** | Nenhuma verificação é perfeita; uma loja legítima recém-aberta pode cair em alguns critérios. O sistema informa e explica; quem decide é o usuário. |
| **Sem raspagem de sites de terceiros** | Comparadores de preço não oferecem API pública e scraping é frágil e contrário aos termos de uso. A primeira versão gera links de comparação. |
| **Lógica isolada em serviços (`app/Services`)** | Cada verificação é uma classe independente: fácil de testar, trocar e, no futuro, mover para outro serviço (ex.: Python). |
| **Começar simples, evoluir por fases** | Novas tecnologias entram quando há um problema concreto que as justifique — não antes. |

---

## 🔒 Segurança

O sistema acessa links enviados por desconhecidos, então:
- Aceita apenas URLs `http`/`https`.
- Bloqueia endereços internos/privados para evitar **SSRF**.
- Usa *timeout* e limite de tamanho em todas as requisições externas.
- Nunca executa nem renderiza o HTML/JavaScript das páginas analisadas — apenas lê o texto.
- Formulários protegidos contra **CSRF**; saída escapada pelo Blade contra **XSS**.
- Chaves de API ficam no `.env`, fora do controle de versão.

---

## 🛠️ Tecnologias

**Em uso:** Laravel 13 · PHP 8.3+ · Blade · SQLite · Pest · Vite · Git

**Planejadas:** Livewire · BrasilAPI · RDAP/Registro.br · Google Safe Browsing · n8n · Docker · API de LLM · banco vetorial · MCP

---

## 🗺️ Roadmap

### Etapa 1 — Fundamentos de Laravel
- [ ] **Fase 1** — Formulário com o link → domínio → CNPJ na página → consulta à Receita *(em andamento)*
- [ ] **Fase 2** — Idade do domínio via RDAP
- [ ] **Fase 3** — Histórico de consultas e cálculo da nota de risco
- [ ] **Fase 4** — Importação automática da lista do Procon (tarefa agendada)
- [ ] **Fase 5** — Verificações em segundo plano com filas e cache
- [ ] **Fase 6** — Contas de usuário e denúncias da comunidade
- [ ] **Fase 7** — Resultados ao vivo com Livewire
- [ ] **Fase 8** — Testes automatizados de cada verificação

### Etapa 2 — IA, agentes e automação
- [ ] **Fase 9** — API REST
- [ ] **Fase 10** — Bot de mensagens com n8n
- [ ] **Fase 11** — Agente explicador
- [ ] **Fase 12** — Agente analisador com ferramentas
- [ ] **Fase 13** — Evals
- [ ] **Fase 14** — RAG com guias e denúncias
- [ ] **Fase 15** — Servidor MCP

---

## 🧠 Como desenvolvo: IA como tutora, não como atalho

Este projeto é também meu laboratório de aprendizado. Uso o **Claude Code** como tutor e par de programação, com regras que eu mesmo defini em um arquivo `CLAUDE.md`:

- **Explicar antes de fazer** — entendo o conceito antes de escrever o código.
- **Eu escrevo, a IA revisa** — o código é meu; a IA aponta melhorias e convenções do framework.
- **Aprovação manual** — leio cada comando e cada alteração antes de autorizar.
- **Conferir a versão** — com o **Laravel Boost** (MCP), o assistente consulta a documentação da versão exata do framework, evitando sugestões de versões antigas.
- **Desconfiar e verificar** — quando a IA assume algo que não corresponde ao meu ambiente, corrijo e registro.

O objetivo não é gerar código rápido, e sim entender cada decisão a ponto de explicá-la.

---

## 🎯 O que este projeto demonstra

| Competência | Onde aparece |
|---|---|
| **Entender o problema antes da solução** | Origem real do projeto; troca do CNPJ pelo link pensando no usuário |
| **Integração entre sistemas** | Receita Federal, Registro.br, Procon e Google em uma única resposta |
| **Uso crítico de IA** | Fluxo de trabalho com Claude Code, CLAUDE.md, MCP e revisão manual |
| **Comunicação para leigos** | Interface e textos pensados para idosos; risco explicado em linguagem simples |
| **Segurança desde o início** | SSRF, CSRF, XSS, segredos fora do Git |
| **Evolução incremental** | Roadmap por fases, histórico de commits pequenos |
| **Visão de IA aplicada** *(planejado)* | Agentes, evals, RAG, MCP e automação com n8n |

---

## 🚀 Como rodar localmente

Pré-requisitos: PHP 8.3+, Composer, Node.js e Git.

```bash
git clone https://github.com/SEU-USUARIO/verifica-loja.git
cd verifica-loja

composer install                 # instala as dependências PHP
cp .env.example .env             # cria o arquivo de configuração (Windows: copy .env.example .env)
php artisan key:generate         # gera a chave de criptografia da aplicação
php artisan migrate              # cria as tabelas no banco SQLite
npm install && npm run build     # instala e compila o front-end

php artisan serve                # acesse http://127.0.0.1:8000
```

---

## ⚠️ Aviso

O Verifica Loja indica **sinais de risco** com base em informações públicas e não garante que um site seja seguro ou fraudulento. Em caso de dúvida, não compre e consulte os canais oficiais de defesa do consumidor.

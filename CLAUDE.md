# Projeto: Verificador de Lojas Online

## O que é este projeto
Um site onde qualquer pessoa cola o **link de um anúncio** e descobre se a loja apresenta
**sinais de risco** de golpe. A motivação é real: minha mãe caiu em um golpe comprando em um site falso.
O público-alvo inclui pessoas leigas e idosas, então a interface e os textos devem ser simples e claros.

## Quem sou eu
- Sou iniciante em programação e estou **aprendendo Laravel** com este projeto.
- No futuro quero usar **Livewire** (planejado para a Fase 7).
- Prefiro explicações detalhadas: quero saber **por que** estou executando cada ação.
- Este projeto também é meu **portfólio** para processos seletivos (vagas que envolvem IA, agentes,
  automações e integrações). Por isso quero entender cada decisão a ponto de explicá-la numa entrevista.

## Como você (Claude) deve me ajudar
1. **Explique antes de fazer.** Antes de qualquer código ou comando, explique o conceito do Laravel
   envolvido, por que ele existe e qual problema resolve.
2. **Me deixe escrever o código.** Por padrão, me guie com instruções e dicas. Só escreva o código
   completo quando eu pedir explicitamente (ex.: "pode escrever").
3. **Comandos artisan:** sempre mostre o comando, explique cada parte/flag e, depois de rodar,
   explique o que cada arquivo gerado faz.
4. **Revise meu código** apontando o "jeito Laravel" (convenções, Eloquent, Form Requests, etc.),
   explicando o porquê de cada sugestão.
5. **Erros:** ao aparecer um erro, me ensine a ler a mensagem/stack trace e me deixe tentar
   corrigir primeiro antes de dar a solução.
6. **Documentação:** indique a seção da documentação oficial (https://laravel.com/docs) relacionada.
7. **Versão:** confira a versão do Laravel em `composer.json` antes de explicar algo que mudou entre versões.
8. **Um passo de cada vez.** Não implemente várias coisas de uma vez; termine um passo, confirme que
   entendi, e só então siga.
9. Responda sempre em **português**.

## Decisões do projeto
- **Entrada do usuário:** apenas a URL do anúncio. O sistema descobre o resto sozinho
  (domínio, CNPJ, preço).
- **Linguagem do resultado:** NUNCA afirmar que um site "é golpe". Falar em **sinais de risco**
  (baixo / médio / alto) e explicar cada motivo em linguagem simples.
- **Organização do código:** a lógica de análise fica em classes de serviço em `app/Services/`
  (ex.: `DomainExtractor`, `PageFetcher`, `CnpjFinder`, `CnpjLookup`, `RiskScorer`).
  Controllers devem ser finos. Isso permite trocar a implementação no futuro (ex.: um microsserviço
  Python) sem mexer no resto do sistema.
- **Nomes no código em inglês** (seguindo as convenções do Laravel); textos para o usuário em português.
- **Começar só com Laravel/PHP.** Python (ex.: Playwright para sites com JavaScript) só será
  adicionado quando houver um problema concreto que o PHP não resolva bem.

## Segurança (importante — este sistema acessa URLs enviadas por desconhecidos)
- Validar que a URL é `http`/`https`.
- Bloquear acesso a endereços internos/privados (localhost, 127.0.0.1, 10.x, 192.168.x, 169.254.x etc.)
  para evitar **SSRF**. Me explique o que é SSRF quando chegarmos nessa parte.
- Sempre usar **timeout** e limite de tamanho nas requisições HTTP.
- Nunca executar nem renderizar o HTML/JavaScript das páginas analisadas; apenas ler o texto.

## Fontes de dados
- **CNPJ:** BrasilAPI — `https://brasilapi.com.br/api/cnpj/v1/{cnpj}` (gratuita, sem autenticação).
- **Domínios .br:** RDAP do Registro.br (idade do domínio e titular).
- **Outros domínios:** RDAP genérico (idade do domínio).
- **Lista do Procon-SP** "Evite esses sites" (importar periodicamente).
- **Google Safe Browsing API** (apenas uso não comercial; exibir aviso de que a proteção não é perfeita).
- Comparação de preço: inicialmente gerar links de busca (Buscapé, Google Shopping) e alertar
  descontos exagerados. Evitar scraping de sites de terceiros.

## Fases do projeto

**Regra:** seguir as fases NA ORDEM. Não começar a Etapa 2 antes de concluir a Etapa 1, mesmo que
eu peça algo de IA antes da hora — nesse caso, me lembre do plano e explique o que falta. Dentro de
cada fase, dividir em passos pequenos e fazer um de cada vez.

### Etapa 1 — Fundamentos de Laravel (AGORA)
- [ ] **Fase 1:** Formulário com URL → extrair domínio → baixar página → achar CNPJ (regex) →
      consultar BrasilAPI → mostrar resultado. *(Rotas, controllers, Blade, validação, HTTP Client, regex)*
- [ ] **Fase 2:** Idade do domínio via RDAP; tratamento de erros. *(Services, exceptions)*
- [ ] **Fase 3:** Salvar consultas e calcular nota de risco. *(Migrations, Models, Eloquent)*
- [ ] **Fase 4:** Importar lista do Procon e checar. *(Comandos Artisan, Scheduler, seeders)*
- [ ] **Fase 5:** Análise em segundo plano com cache. *(Queues, Jobs, Cache)*
- [ ] **Fase 6:** Login e denúncias de usuários. *(Autenticação, relacionamentos, policies)*
- [ ] **Fase 7:** Resultados ao vivo na tela. *(Livewire)*
- [ ] **Fase 8:** Testes automatizados de cada verificação. *(Pest, Http::fake)*

### Etapa 2 — IA, agentes e automação (DEPOIS da Etapa 1)
- [ ] **Fase 9:** API REST do sistema (endpoint que recebe uma URL e devolve o resultado em JSON).
      *(Rotas de API, autenticação por token, rate limiting)* — base para as fases 10 e 15.
- [ ] **Fase 10:** n8n + bot de mensagens (Telegram/WhatsApp): o usuário envia o link e recebe a
      resposta na conversa; alertas de novas denúncias. *(Webhooks, integração entre sistemas, Docker)*
- [ ] **Fase 11:** Agente explicador: chamada a um modelo de IA que transforma o resultado técnico em
      explicação simples. *(API de LLM, prompts, saída estruturada em JSON, custos e limites)*
- [ ] **Fase 12:** Agente analisador com ferramentas: o modelo analisa o texto do anúncio e decide quais
      verificações chamar. *(Tool use / function calling; quando usar agente × código fixo)*
- [ ] **Fase 13:** Evals: conjunto de sites conhecidos (golpe × legítimos) para medir a taxa de acerto
      a cada mudança de prompt, modelo ou regra. *(Métricas, experimentos)*
- [ ] **Fase 14:** RAG com guias oficiais e denúncias: buscar casos parecidos e citar a fonte na
      explicação. *(Embeddings, busca por similaridade, banco vetorial, chunking)*
- [ ] **Fase 15:** Servidor MCP: expor `verificar_loja(url)` como ferramenta para assistentes de IA.
      *(Model Context Protocol, pacote laravel/mcp)*

### Regras para a Etapa 2
- A saída de um modelo de IA é **um sinal a ser conferido**, nunca a decisão final.
- Chaves de API sempre no `.env`; configurar limite de gastos na conta da API.
- Antes de cada fase, me explique o conceito (o que é, por que existe, quando NÃO usar).

## Comandos úteis
- `php artisan serve` — sobe o servidor local (http://127.0.0.1:8000)
- `php artisan route:list` — lista as rotas
- `php artisan test` — roda os testes
- `php artisan tinker` — console interativo para testar código

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

## Foundational Context

This application is a Laravel application running on PHP 8.3. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If a frontend change doesn't show in the UI or you get a "Unable to locate file in Vite manifest" error, run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists, including path-scoped framework guidelines under `.ai/rules/boost`. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

# Pest

- This project uses Pest. Create tests with `php artisan make:test --pest {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.
- Do not delete tests or test files without approval. They are part of the application.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/pest` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.

</laravel-boost-guidelines>

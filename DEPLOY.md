# Deploy no Railway

## 0. Antes de tudo: rotacionar segredos vazados

O `.env` deste projeto estava versionado no git (histórico do GitHub), e o token do Mercado
Pago e uma API key do Heroku estavam hardcoded no código-fonte. Esses valores já foram
publicados e **precisam ser trocados** antes (ou logo depois) do deploy, senão o deploy novo
vai nascer usando credenciais que qualquer pessoa com acesso ao histórico do repositório
consegue ver:

- **Senha do MySQL (AWS RDS)**: trocar a senha do usuário do banco no painel da AWS/RDS.
- **Cloudinary**: gerar um novo API secret em https://console.cloudinary.com/settings/security e atualizar `CLOUDINARY_URL`.
- **Mercado Pago**: gerar um novo Access Token de produção no painel de credenciais e usar o novo valor em `MERCADOPAGO_ACCESS_TOKEN`.
- **Heroku**: revogar a API key antiga em https://dashboard.heroku.com/account (a lógica que a usava foi removida do código, então ela não é mais necessária de qualquer forma).

## 1. Criar o projeto no Railway

1. Crie um novo projeto no Railway e conecte o repositório `marrige` (backend) do GitHub.
2. Railway vai detectar o `composer.json` e usar o builder Nixpacks automaticamente (configurado em `railway.json`). O Nixpacks monta seu próprio servidor (nginx + php-fpm) apontando pra `public/` — **não** defina um `startCommand` customizado nem adicione um `Procfile`, isso substitui o servidor que o Nixpacks já prepara e derruba o healthcheck.
3. As migrations rodam sozinhas antes de cada deploy via `deploy.preDeployCommand` no `railway.json` (`php artisan migrate --force`), e o healthcheck está configurado em `/api`.

## 2. Variáveis de ambiente a configurar no Railway

Copie do `.env.example` e preencha com valores reais (novos, já rotacionados):

| Variável | Valor |
|---|---|
| `APP_NAME` | Nome do app |
| `APP_ENV` | `production` |
| `APP_KEY` | Gere localmente com `php artisan key:generate --show` (não modifica seu .env, só imprime a chave) |
| `APP_DEBUG` | `false` |
| `APP_URL` | URL pública que o Railway vai gerar (ex: `https://seu-app.up.railway.app`) — dá pra confirmar/ajustar depois do primeiro deploy |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` / `DB_PORT` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | Dados do RDS (com a senha já rotacionada) |
| `CLOUDINARY_URL` | Nova URL com o secret rotacionado |
| `MERCADOPAGO_ACCESS_TOKEN` | Novo access token de produção |
| `MERCADOPAGO_NOTIFICATION_URL` | Deixe em branco — cai automaticamente em `{APP_URL}/api/webhook_payment` |
| `CORS_ALLOWED_ORIGINS` | URL do frontend na Vercel (ex: `https://seu-site.vercel.app`). Pode ter mais de um domínio separado por vírgula |
| `LOG_CHANNEL` | `stderr` (fica visível nos logs do Railway) |
| `QUEUE_CONNECTION` | `sync` (não há nada usando fila de verdade hoje; se algo passar a usar `dispatch()`, revisite isso) |
| `SESSION_DRIVER` | `file` |

## 3. Primeiro deploy

1. Suba as variáveis acima no Railway e faça o deploy.
2. Depois que o app subir, confira `https://SEU-APP.up.railway.app/api` — deve responder `{"status":"success","data":"REST API Funcionando!"}`.
3. Ajuste `APP_URL` para a URL real gerada pelo Railway (e redeploy) se ainda não tiver colocado certo.
4. Se usar domínio próprio, adicione-o no Railway e atualize `APP_URL` e `CORS_ALLOWED_ORIGINS` de novo.

## 4. Depois: atualizar o frontend

No `marrige-front`, edite `src/environments/environment.prod.ts` trocando `apiUrl` pela URL real
do backend no Railway (`https://SEU-APP.up.railway.app/api`) antes de fazer o deploy na Vercel.
Veja `marrige-front/DEPLOY.md`.

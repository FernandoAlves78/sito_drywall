# Google Calendar — Ricontattare cliente

Quando imposti lo stato **Ricontattare cliente** e salvi data/ora, il sistema crea (o aggiorna) un evento nel tuo Google Calendar.

## 1. Google Cloud Console

1. Acede a [Google Cloud Console](https://console.cloud.google.com/).
2. Cria um projeto (ou usa um existente).
3. Ativa **Google Calendar API** (APIs & Services → Library).
4. Em **OAuth consent screen**, configura como **External** ou **Internal** e adiciona o scope `https://www.googleapis.com/auth/calendar`.
5. Em **Credentials** → **Create Credentials** → **OAuth client ID**:
   - Tipo: **Web application** (ou Desktop se preferires o fluxo local).
   - Authorized redirect URIs: `https://developers.google.com/oauthplayground` (para obter o refresh token no passo 2).

Anota **Client ID** e **Client Secret**.

## 2. Obter o Refresh Token (OAuth Playground)

1. Abre [OAuth 2.0 Playground](https://developers.google.com/oauthplayground).
2. Clica no ícone de engrenagem (⚙️) → marca **Use your own OAuth credentials** e cola Client ID e Secret.
3. No passo 1, seleciona **Google Calendar API v3** → `https://www.googleapis.com/auth/calendar`.
4. **Authorize APIs** → inicia sessão com a conta Google onde queres os eventos.
5. **Exchange authorization code for tokens**.
6. Copia o **Refresh token** (não expira enquanto não revogares a app).

## 3. Variáveis no `.env`

```env
GOOGLE_CALENDAR_CLIENT_ID=seu-client-id.apps.googleusercontent.com
GOOGLE_CALENDAR_CLIENT_SECRET=seu-client-secret
GOOGLE_CALENDAR_REFRESH_TOKEN=seu-refresh-token
GOOGLE_CALENDAR_ID=primary
GOOGLE_CALENDAR_EVENT_DURATION=30
```

- `GOOGLE_CALENDAR_ID`: usa `primary` para o calendário principal, ou o ID de um calendário específico (encontra-se nas definições do Google Calendar).
- `GOOGLE_CALENDAR_EVENT_DURATION`: duração do evento em minutos (predefinição: 30).

## 4. Migrar a base de dados

```bash
php artisan migrate
```

(A integração usa a API REST do Google via HTTP — não é necessário instalar pacotes Composer adicionais.)

## 5. Utilização no backoffice

1. Abre un preventivo → **Dettaglio**.
2. Stato: **Ricontattare cliente**.
3. Escolhe **data e ora** do ricontatto.
4. **Salva modifiche** → o evento é criado no Google Calendar com nome, telefone, messaggio e link ao pannello.

Se alterares data/ora ou tornares a guardar, o evento é **atualizado**. Se mudares lo stato para outro valor, o evento é **eliminado** do calendário.

## Resolução de problemas

- **Evento não criado**: verifica o `.env`, logs em `storage/logs/laravel.log` e se a API Calendar está ativa.
- **Token inválido**: gera um novo refresh token no OAuth Playground.
- **Fuso horário**: definido em `APP_TIMEZONE` (ex.: `Europe/Rome`).

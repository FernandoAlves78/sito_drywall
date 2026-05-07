# Alves Drywall e Pittura — Site

Site institucional para **Alves Drywall & Pittura** (drywall, pintura e acabamentos), com formulários de orçamento, registo e recensões. Frontend estático em `public/` e API leve em **Node.js** + **Express**, com persistência em ficheiro JSON.

## Requisitos

- [Node.js](https://nodejs.org/) (versão LTS recomendada)

## Instalação

```bash
npm install
```

## Executar localmente

```bash
npm start
```

O servidor sobe em **http://localhost:3000** e serve os ficheiros estáticos da pasta `public/`.

## Estrutura do projeto

| Caminho | Descrição |
|---------|-----------|
| `server.js` | Servidor Express, rotas da API e leitura/escrita de `db.json` |
| `public/` | HTML, CSS, JS e imagens do site |
| `db.json` | Base de dados simples (utilizadores, pedidos de orçamento, recensões) |

## API (JSON)

Todas as rotas `POST` esperam corpo em JSON (`Content-Type: application/json`).

| Método | Rota | Descrição |
|--------|------|-----------|
| `POST` | `/register` | Registo de utilizador (`users` em `db.json`) |
| `POST` | `/preventivo` | Pedido de orçamento (`preventivi`) |
| `POST` | `/recensioni` | Nova recensão (`recensioni`) |
| `GET` | `/recensioni` | Lista de recensões |

## Repositório

Código-fonte: [github.com/FernandoAlves78/sito_drywall](https://github.com/FernandoAlves78/sito_drywall)

## Licença

ISC (ver `package.json`).

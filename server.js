const express = require('express');
const fs = require('fs');
const app = express();

app.use(express.json());
app.use(express.static('public'));

const DB_FILE = 'db.json';

// Ler banco
function readDB() {
  return JSON.parse(fs.readFileSync(DB_FILE));
}

// Salvar banco
function saveDB(data) {
  fs.writeFileSync(DB_FILE, JSON.stringify(data, null, 2));
}

// Cadastro
app.post('/register', (req, res) => {
  const db = readDB();
  db.users.push(req.body);
  saveDB(db);
  res.send({ message: "Registrazione completata!" });
});

// Orçamento
app.post('/preventivo', (req, res) => {
  const db = readDB();
  db.preventivi.push(req.body);
  saveDB(db);
  res.send({ message: "Richiesta inviata!" });
});

// Reviews
app.post('/recensioni', (req, res) => {
  const db = readDB();
  db.recensioni.push(req.body);
  saveDB(db);
  res.send({ message: "Recensione aggiunta!" });
});

// Listar reviews
app.get('/recensioni', (req, res) => {
  const db = readDB();
  res.send(db.recensioni);
});

app.listen(3000, () => {
  console.log("Server attivo su http://localhost:3000");
});
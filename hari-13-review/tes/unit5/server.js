const express = require("express");
const bodyParser = require("body-parser");
const mysql = require("mysql");

const app = express();
const PORT = 3000;

app.use(bodyParser.json());

// koneksi ke database 'ujian'
const db = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "",
  database: "ujian",
});

db.connect((err) => {
  if (err) {
    console.error("Koneksi database gagal:", err.message);
    return;
  }
  console.log("Terhubung ke database 'ujian'");
});

// GET / -> JSON isi tabel users
app.get("/", (req, res) => {
  db.query("SELECT id, first_name, last_name FROM users", (err, hasil) => {
    if (err) {
      console.error(err);
      return res.status(500).json({ error: "Terjadi kesalahan pada server." });
    }
    res.json({ data: hasil });
  });
});

app.listen(PORT, () => {
  console.log(`Server berjalan di http://localhost:${PORT}`);
});

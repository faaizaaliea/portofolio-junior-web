const http = require("http");
const fs = require("fs");
const path = require("path");

const PORT = 8000;
const FOLDER_PUBLIK = path.join(__dirname, "public");
const TIPE = {
  ".html": "text/html; charset=utf-8",
  ".css": "text/css; charset=utf-8",
  ".js": "text/javascript; charset=utf-8",
  ".json": "application/json; charset=utf-8",
  ".png": "image/png",
  ".jpg": "image/jpeg",
  ".svg": "image/svg+xml",
  ".txt": "text/plain; charset=utf-8",
};

function kirim(res, status, tipe, isi, header = {}) {
  res.writeHead(status, { "Content-Type": tipe, ...header });
  res.end(isi);
}

const teks = "text/plain; charset=utf-8";

const server = http.createServer((req, res) => {
  try {
    if (req.method !== "GET") {
      return kirim(res, 405, teks, "Metode tidak diizinkan.", { Allow: "GET" });
    }

    const url = new URL(req.url, "http://localhost");

    // endpoint "/" mengembalikan hello world
    if (url.pathname === "/") {
      return kirim(res, 200, teks, "hello world");
    }

    // selain itu: static file server dari folder public/
    const jalur = decodeURIComponent(url.pathname);
    if (jalur.includes("\0")) {
      return kirim(res, 400, teks, "Permintaan tidak valid.");
    }
    const berkas = path.join(FOLDER_PUBLIK, path.normalize(jalur));
    if (!berkas.startsWith(FOLDER_PUBLIK + path.sep)) {
      return kirim(res, 403, teks, "Dilarang.");
    }

    fs.readFile(berkas, (err, data) => {
      if (err) {
        if (err.code === "ENOENT" || err.code === "EISDIR") {
          return kirim(res, 404, teks, "Berkas tidak ditemukan.");
        }
        console.error(err);
        return kirim(res, 500, teks, "Terjadi kesalahan pada server.");
      }
      const tipe = TIPE[path.extname(berkas).toLowerCase()] || "application/octet-stream";
      kirim(res, 200, tipe, data);
    });
  } catch (err) {
    // URL rusak (mis. %zz) atau error tak terduga: jangan sampai crash
    console.error(err);
    kirim(res, 400, teks, "Permintaan tidak valid.");
  }
});

server.on("error", (err) => {
  if (err.code === "EADDRINUSE") {
    console.error(`Port ${PORT} sudah dipakai aplikasi lain.`);
  } else {
    console.error(err);
  }
  process.exit(1);
});

server.listen(PORT, () => {
  console.log(`Server berjalan di http://localhost:${PORT}`);
});

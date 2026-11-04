import { hitung } from "./operasi.js";

const layarEl = document.getElementById("layar");
const papan = document.getElementById("papan");
const riwayatEl = document.getElementById("riwayat");

const MAKS_RIWAYAT = 5;
const PRESISI_DESIMAL = 10;
const riwayat = [];

let layar = "0";
let angkaPertama = null;
let operator = null;
let siapReset = false;

/** Menampilkan isi variabel layar ke elemen layar. */
function perbaruiLayar() {
  layarEl.textContent = layar;
}

/** Menampilkan riwayat perhitungan; teks dipasang dengan textContent. */
function perbaruiRiwayat() {
  riwayatEl.replaceChildren(
    ...riwayat.map((teks) => {
      const li = document.createElement("li");
      li.textContent = teks;
      return li;
    }),
  );
}

/**
 * Menambahkan satu digit atau titik desimal ke layar.
 * @param {string} digit - Karakter '0'-'9' atau '.'.
 */
function masukkanAngka(digit) {
  if (siapReset) {
    layar = digit === "." ? "0." : digit;
    siapReset = false;
    return;
  }
  if (digit === "." && layar.includes(".")) return;
  if (layar === "0" && digit !== ".") {
    layar = digit;
  } else {
    layar += digit;
  }
}

/**
 * Menjalankan perhitungan yang tertunda. Galat (mis. bagi nol) ditampilkan
 * sebagai pesan di layar, lalu kalkulator siap dipulihkan dengan C atau angka.
 */
function hitungHasil() {
  if (operator === null || siapReset) return;
  const b = Number(layar);
  try {
    const hasil = Number(
      hitung(operator, angkaPertama, b).toFixed(PRESISI_DESIMAL),
    );
    riwayat.push(`${angkaPertama} ${operator} ${b} = ${hasil}`);
    if (riwayat.length > MAKS_RIWAYAT) riwayat.shift();
    perbaruiRiwayat();
    layar = String(hasil);
  } catch (error) {
    layar = error.message;
  }
  operator = null;
  angkaPertama = null;
  siapReset = true;
}

/**
 * Memilih operator. Bila sudah ada operator tertunda, dihitung dulu.
 * Menekan operator berturut-turut memakai operator yang terakhir.
 * @param {string} op - Salah satu dari '+', '-', '*', '/'.
 */
function pilihOperator(op) {
  if (operator !== null && !siapReset) hitungHasil();
  const nilai = Number(layar);
  if (Number.isNaN(nilai)) return; // layar berisi pesan galat
  angkaPertama = nilai;
  operator = op;
  siapReset = true;
}

/** Mengembalikan kalkulator ke keadaan awal (tombol C). */
function bersihkan() {
  layar = "0";
  angkaPertama = null;
  operator = null;
  siapReset = false;
}

/** Menghapus satu karakter terakhir pada layar. */
function hapusSatu() {
  if (siapReset) return;
  layar = layar.slice(0, -1);
  if (layar === "" || layar === "-") layar = "0";
}

papan.addEventListener("click", (event) => {
  const tombol = event.target.closest("button");
  if (!tombol) return;
  if (tombol.dataset.angka !== undefined) masukkanAngka(tombol.dataset.angka);
  else if (tombol.dataset.operator) pilihOperator(tombol.dataset.operator);
  else if (tombol.dataset.aksi === "sama") hitungHasil();
  else if (tombol.dataset.aksi === "hapus") bersihkan();
  else if (tombol.dataset.aksi === "hapus-satu") hapusSatu();
  perbaruiLayar();
});

// Tantangan: dukungan keyboard
document.addEventListener("keydown", (event) => {
  // Biarkan pintasan browser (Ctrl+C, Ctrl++, Ctrl+- dan sebagainya) bekerja
  if (event.ctrlKey || event.metaKey || event.altKey) return;

  const { key } = event;
  if (/^[0-9]$/.test(key)) masukkanAngka(key);
  else if (key === "." || key === ",") masukkanAngka(".");
  else if (["+", "-", "*", "/"].includes(key)) pilihOperator(key);
  else if (key === "Enter" || key === "=") hitungHasil();
  else if (key === "Backspace") hapusSatu();
  else if (key === "Escape" || key.toLowerCase() === "c") bersihkan();
  else return;
  event.preventDefault();
  perbaruiLayar();
});

perbaruiLayar();
perbaruiRiwayat();

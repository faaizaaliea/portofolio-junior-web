import { Queue } from "../../structures.js";

const KUNCI_SIMPAN = "antrean-loket";
const BATAS_RIWAYAT = 5;

const elDipanggil = document.getElementById("dipanggil");
const elJumlah = document.getElementById("jumlah");
const elDaftar = document.getElementById("daftar");
const elRiwayat = document.getElementById("riwayat");
const elTanggal = document.getElementById("tanggal");

const antrian = new Queue();
let nomorTerakhir = 0;
let sedangDipanggil = "-";
let riwayat = []; // terbaru di depan, maksimal BATAS_RIWAYAT

/** @returns {string} Tanggal lokal hari ini, format YYYY-MM-DD. */
function tanggalHariIni() {
  return new Date().toLocaleDateString("sv-SE");
}

/**
 * @param {number} angka
 * @returns {string} Contoh: 7 menjadi "A007".
 */
function formatNomor(angka) {
  return "A" + String(angka).padStart(3, "0");
}

/** Menyimpan keadaan ke localStorage (diabaikan bila tidak tersedia). */
function simpan() {
  try {
    localStorage.setItem(
      KUNCI_SIMPAN,
      JSON.stringify({
        tanggal: tanggalHariIni(),
        nomorTerakhir,
        sedangDipanggil,
        riwayat,
        antrean: antrian.toArray(),
      }),
    );
  } catch {
    // localStorage bisa diblokir; aplikasi tetap jalan tanpa penyimpanan.
  }
}

/** Memuat keadaan hari ini. Data hari lain dibuang (nomor mulai dari 1 lagi). */
function muat() {
  try {
    const data = JSON.parse(localStorage.getItem(KUNCI_SIMPAN));
    if (data?.tanggal !== tanggalHariIni()) {
      return;
    }
    nomorTerakhir = data.nomorTerakhir;
    sedangDipanggil = data.sedangDipanggil;
    riwayat = data.riwayat;
    data.antrean.forEach((nomor) => antrian.enqueue(nomor));
  } catch {
    // data rusak atau tidak ada: mulai dari kosong
  }
}

/**
 * Mengisi <ol> dengan teks (textContent, aman dari XSS).
 * @param {HTMLElement} daftar
 * @param {string[]} isi
 * @param {string} teksKosong
 */
function isiDaftar(daftar, isi, teksKosong) {
  daftar.replaceChildren();
  if (isi.length === 0) {
    const li = document.createElement("li");
    li.className = "kosong";
    li.textContent = teksKosong;
    daftar.append(li);
    return;
  }
  for (const nomor of isi) {
    const li = document.createElement("li");
    li.textContent = nomor;
    daftar.append(li);
  }
}

/** Menggambar ulang seluruh tampilan dari keadaan saat ini. */
function tampilkan() {
  elTanggal.textContent = tanggalHariIni();
  elDipanggil.textContent = sedangDipanggil;
  elJumlah.textContent = antrian.size();
  isiDaftar(elDaftar, antrian.toArray(), "Belum ada yang menunggu");
  isiDaftar(elRiwayat, riwayat, "Belum ada yang dipanggil");
}

/** Mengambil nomor baru dan menaruhnya di belakang antrean. */
function ambilNomor() {
  nomorTerakhir++;
  antrian.enqueue(formatNomor(nomorTerakhir));
}

/** Memanggil nomor terdepan; bila kosong, keadaan tidak berubah. */
function panggilBerikutnya() {
  if (antrian.isEmpty()) {
    return false;
  }
  sedangDipanggil = antrian.dequeue();
  riwayat = [sedangDipanggil, ...riwayat].slice(0, BATAS_RIWAYAT);
  return true;
}

/** Mengosongkan antrean, riwayat, dan nomor kembali ke 1. */
function resetAntrean() {
  antrian.clear();
  nomorTerakhir = 0;
  sedangDipanggil = "-";
  riwayat = [];
}

document.getElementById("ambil").addEventListener("click", () => {
  ambilNomor();
  simpan();
  tampilkan();
});

document.getElementById("panggil").addEventListener("click", () => {
  if (!panggilBerikutnya()) {
    // Pesan terpisah agar nomor yang terakhir dipanggil tidak tertimpa.
    elDipanggil.textContent = "Antrean kosong";
    return;
  }
  simpan();
  tampilkan();
});

document.getElementById("reset").addEventListener("click", () => {
  if (!confirm("Reset antrean dan mulai nomor dari A001?")) {
    return;
  }
  resetAntrean();
  simpan();
  tampilkan();
});

muat();
tampilkan();

// Lab 1 - Array dan Objek (data peserta).
import { fileURLToPath } from "node:url";

const peserta = [
  { id: 1, nama: "Sari", kelas: "Web", nilai: 90, aktif: true },
  { id: 2, nama: "Budi", kelas: "Web", nilai: 68, aktif: true },
  { id: 3, nama: "Citra", kelas: "Data", nilai: 82, aktif: false },
  { id: 4, nama: "Supri", kelas: "Data", nilai: 80, aktif: true },
  { id: 5, nama: "Yanto", kelas: "Web", nilai: 50, aktif: true },
  { id: 6, nama: "Mariyadi", kelas: "Data", nilai: 70, aktif: false },
  { id: 7, nama: "Siti", kelas: "Data", nilai: 81, aktif: true },
  { id: 8, nama: "Paijo", kelas: "Data", nilai: 73, aktif: true },
  { id: 9, nama: "Cipluk", kelas: "Web", nilai: 87, aktif: true },
  { id: 10, nama: "Joni", kelas: "Web", nilai: 90, aktif: false },
];

/**
 * 1. Peserta yang aktif.
 * @param {Array<object>} daftar
 * @returns {Array<object>} Array baru.
 */
export function pesertaAktif(daftar) {
  return daftar.filter((p) => p.aktif);
}

/**
 * 2. Urut menurut nama tanpa mengubah array asli.
 * @param {Array<object>} daftar
 * @returns {Array<object>} Salinan terurut.
 */
export function urutkanBerdasarNama(daftar) {
  return [...daftar].sort((a, b) => a.nama.localeCompare(b.nama));
}

/**
 * 3. Cari menurut id.
 * @param {Array<object>} daftar
 * @param {number} id
 * @returns {object|null} Peserta, atau null bila tidak ditemukan.
 */
export function cariPeserta(daftar, id) {
  return daftar.find((p) => p.id === id) ?? null;
}

/**
 * 4. Jumlah peserta per kelas. Memakai objek tanpa prototipe agar nama kelas
 * seperti "constructor" tidak bentrok dengan properti bawaan Object.
 * @param {Array<object>} daftar
 * @returns {Object<string, number>}
 */
export function hitungPerKelas(daftar) {
  const jumlah = Object.create(null);
  for (const p of daftar) {
    jumlah[p.kelas] = (jumlah[p.kelas] ?? 0) + 1;
  }
  return jumlah;
}

/**
 * 5a. Menambah peserta (array asli tidak diubah).
 * @param {Array<object>} daftar
 * @param {object} baru
 * @returns {Array<object>} Array baru.
 */
export function tambahPeserta(daftar, baru) {
  const hasil = [...daftar];
  hasil.push(baru);
  return hasil;
}

/**
 * 5b. Mengubah properti peserta menurut id (array asli tidak diubah).
 * @param {Array<object>} daftar
 * @param {number} id
 * @param {object} dataBaru Properti yang diganti.
 * @returns {Array<object>} Array baru; sama isinya bila id tidak ada.
 */
export function ubahPeserta(daftar, id, dataBaru) {
  return daftar.map((p) => (p.id === id ? { ...p, ...dataBaru } : p));
}

/**
 * 5c. Menghapus peserta menurut id (array asli tidak diubah).
 * @param {Array<object>} daftar
 * @param {number} id
 * @returns {Array<object>} Array baru.
 */
export function hapusPeserta(daftar, id) {
  return daftar.filter((p) => p.id !== id);
}

/**
 * 6. Indeks menurut id sehingga pencarian menjadi O(1).
 * @param {Array<object>} daftar
 * @returns {Object<number, object>}
 */
export function buatIndeksPeserta(daftar) {
  return daftar.reduce((indeks, p) => {
    indeks[p.id] = p;
    return indeks;
  }, {});
}

function main() {
  console.log("1. Peserta aktif:");
  console.table(pesertaAktif(peserta), ["id", "nama", "kelas", "nilai"]);

  console.log("2. Urut berdasarkan nama:");
  console.log(urutkanBerdasarNama(peserta).map((p) => p.nama));
  console.log(
    "   Array asli tetap:",
    peserta.slice(0, 3).map((p) => p.nama),
    "...",
  );

  console.log("3. Cari id 3:", cariPeserta(peserta, 3));
  console.log("   Cari id 12:", cariPeserta(peserta, 12), "(tidak ditemukan)");

  console.log("4. Jumlah per kelas:", { ...hitungPerKelas(peserta) });

  let data = tambahPeserta(peserta, {
    id: 11,
    nama: "Rina",
    kelas: "Web",
    nilai: 85,
    aktif: true,
  });
  console.log("5a. Setelah tambah Rina:", data.length, "peserta");

  data = ubahPeserta(data, 2, { nilai: 75 });
  console.log("5b. Nilai Budi sekarang:", cariPeserta(data, 2).nilai);

  data = hapusPeserta(data, 3);
  console.log(
    "5c. Setelah hapus id 3:",
    data.length,
    "peserta;",
    "id 3 =",
    cariPeserta(data, 3),
  );

  const indeks = buatIndeksPeserta(data);
  console.log("6. Indeks id 5:", indeks[5]);
  console.log("   Indeks id 3 (sudah dihapus):", indeks[3]);
}

if (process.argv[1] === fileURLToPath(import.meta.url)) {
  main();
}

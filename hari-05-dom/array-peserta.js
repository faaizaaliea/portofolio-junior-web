// Lab 2 - Array dan method array
const peserta = [
  { id: 1, nama: "Sari", kelas: "Web", nilai: 90 },
  { id: 2, nama: "Budi", kelas: "Web", nilai: 68 },
  { id: 3, nama: "Citra", kelas: "Data", nilai: 82 },
  { id: 4, nama: "Dina", kelas: "Web", nilai: 75 },
  { id: 5, nama: "Eko", kelas: "Data", nilai: 88 },
  { id: 6, nama: "Fani", kelas: "Data", nilai: 65 },
  { id: 7, nama: "Gilang", kelas: "Web", nilai: 78 },
  { id: 8, nama: "Hana", kelas: "Data", nilai: 95 },
  { id: 9, nama: "Indra", kelas: "Web", nilai: 60 },
  { id: 10, nama: "Joko", kelas: "Data", nilai: 72 },
  { id: 11, nama: "Kiki", kelas: "Web", nilai: 85 },
];

const BATAS_LULUS = 70;

/**
 * Mengambil nama peserta yang lulus.
 * @param {{nama: string, nilai: number}[]} daftar - Daftar peserta.
 * @returns {string[]} Nama peserta dengan nilai >= BATAS_LULUS.
 */
function daftarLulus(daftar) {
  return daftar.filter((p) => p.nilai >= BATAS_LULUS).map((p) => p.nama);
}

/**
 * Menghitung rata-rata nilai per kelas.
 * @param {{kelas: string, nilai: number}[]} daftar - Daftar peserta.
 * @returns {Object<string, number>} Rata-rata nilai dengan kunci nama kelas.
 */
function rataRataPerKelas(daftar) {
  const hasil = {};

  for (const p of daftar) {
    hasil[p.kelas] ??= { total: 0, jumlah: 0 };
    hasil[p.kelas].total += p.nilai;
    hasil[p.kelas].jumlah++;
  }

  for (const kelas in hasil) {
    hasil[kelas] = hasil[kelas].total / hasil[kelas].jumlah;
  }
  return hasil;
}

/**
 * Mencari peserta dengan nilai tertinggi.
 * @param {{nilai: number}[]} daftar - Daftar peserta.
 * @returns {object|null} Peserta bernilai tertinggi, atau null bila daftar kosong.
 */
function pesertaTertinggi(daftar) {
  if (daftar.length === 0) return null;
  return daftar.reduce((tertinggi, p) =>
    p.nilai > tertinggi.nilai ? p : tertinggi,
  );
}

/**
 * Mencari peserta berdasarkan sebagian nama (tidak peka huruf besar-kecil).
 * @param {{nama: string}[]} daftar - Daftar peserta.
 * @param {string} kataKunci - Potongan nama yang dicari.
 * @returns {object[]} Peserta yang namanya memuat kata kunci.
 */
function cariPeserta(daftar, kataKunci) {
  const kunci = kataKunci.toLowerCase();
  return daftar.filter((p) => p.nama.toLowerCase().includes(kunci));
}

/**
 * Membuat daftar peringkat dengan nomor urut dari nilai tertinggi.
 * @param {{nama: string, nilai: number}[]} daftar - Daftar peserta (tidak diubah).
 * @returns {{peringkat: number, nama: string, nilai: number}[]} Daftar peringkat.
 */
function daftarPeringkat(daftar) {
  return [...daftar]
    .sort((a, b) => b.nilai - a.nilai)
    .map((p, index) => ({
      peringkat: index + 1,
      nama: p.nama,
      nilai: p.nilai,
    }));
}

/**
 * Menghapus duplikat dari daftar.
 * @param {string[]} daftar - Daftar email (boleh berulang).
 * @returns {string[]} Daftar tanpa duplikat, urutan kemunculan pertama terjaga.
 */
function hapusDuplikat(daftar) {
  return [...new Set(daftar)];
}

const email = [
  "sari@email.com",
  "budi@email.com",
  "sari@email.com",
  "citra@email.com",
  "budi@email.com",
];

console.log("1. Peserta lulus:", daftarLulus(peserta));
console.log("2. Rata-rata per kelas:", rataRataPerKelas(peserta));
console.log("3. Nilai tertinggi:", pesertaTertinggi(peserta));
console.log("4. Hasil pencarian 'an':", cariPeserta(peserta, "an"));
console.log("5. Daftar peringkat:", daftarPeringkat(peserta));
console.log("6. Email unik:", hapusDuplikat(email));

// Kasus tepi: array kosong tidak boleh menyebabkan error
console.assert(pesertaTertinggi([]) === null, "daftar kosong harus null");
console.assert(daftarLulus([]).length === 0, "daftar kosong harus []");
console.assert(
  cariPeserta(peserta, "HANA").length === 1,
  "pencarian tidak peka huruf",
);

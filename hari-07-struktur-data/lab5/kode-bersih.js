// Versi bersih dari kode-buruk.js (Lab 4)

const PAJAK = 0.11;
const BATAS_GRADE = { A: 85, B: 70, C: 55 };
const BATAS_NILAI_BAGUS = 80;

/**
 * Menghitung rata-rata nilai.
 * @param {number[]} daftarNilai Daftar nilai.
 * @returns {number} Rata-rata, atau 0 bila daftar kosong.
 */
function hitungRataRata(daftarNilai) {
  if (!Array.isArray(daftarNilai) || daftarNilai.length === 0) return 0;
  const jumlah = daftarNilai.reduce((total, nilai) => total + nilai, 0);
  return jumlah / daftarNilai.length;
}

/**
 * Menentukan grade huruf dari sebuah nilai.
 * @param {number} nilai Nilai angka.
 * @returns {'A'|'B'|'C'|'D'} Grade huruf.
 */
function tentukanGrade(nilai) {
  if (nilai >= BATAS_GRADE.A) return 'A';
  if (nilai >= BATAS_GRADE.B) return 'B';
  if (nilai >= BATAS_GRADE.C) return 'C';
  return 'D';
}

/**
 * Menghitung harga setelah ditambah pajak.
 * @param {number} harga Harga sebelum pajak.
 * @returns {number} Harga termasuk pajak.
 */
function hitungHargaDenganPajak(harga) {
  return harga * (1 + PAJAK);
}

const daftarNilai = [80, 65, 90];
const rataRata = hitungRataRata(daftarNilai);

if (rataRata >= BATAS_NILAI_BAGUS) {
  console.log('Bagus');
}
console.log(tentukanGrade(rataRata));
console.log(hitungHargaDenganPajak(100000));

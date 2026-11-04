/**
 * Menjumlahkan dua angka.
 * @param {number} a - Angka pertama.
 * @param {number} b - Angka kedua.
 * @returns {number} Hasil a + b.
 */
export const tambah = (a, b) => a + b;

/**
 * Mengurangkan dua angka.
 * @param {number} a - Angka pertama.
 * @param {number} b - Angka kedua.
 * @returns {number} Hasil a - b.
 */
export const kurang = (a, b) => a - b;

/**
 * Mengalikan dua angka.
 * @param {number} a - Angka pertama.
 * @param {number} b - Angka kedua.
 * @returns {number} Hasil a * b.
 */
export const kali = (a, b) => a * b;

/**
 * Membagi dua angka.
 * @param {number} a - Pembilang.
 * @param {number} b - Penyebut.
 * @returns {number} Hasil a / b.
 * @throws {Error} Bila b bernilai 0.
 */
export function bagi(a, b) {
  if (b === 0) throw new Error("Tidak bisa dibagi nol");
  return a / b;
}

/**
 * Menjalankan operasi sesuai simbol operator.
 * @param {string} operator - Salah satu dari '+', '-', '*', '/'.
 * @param {number} a - Angka pertama.
 * @param {number} b - Angka kedua.
 * @returns {number} Hasil perhitungan.
 * @throws {Error} Bila operator tidak dikenal atau terjadi pembagian nol.
 */
export function hitung(operator, a, b) {
  switch (operator) {
    case "+":
      return tambah(a, b);
    case "-":
      return kurang(a, b);
    case "*":
      return kali(a, b);
    case "/":
      return bagi(a, b);
    default:
      throw new Error("Operator tidak dikenal");
  }
}

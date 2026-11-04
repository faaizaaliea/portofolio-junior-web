/**
 * Menghitung rata-rata dari daftar nilai.
 * @param {number[]} daftar - Array angka.
 * @returns {number} Rata-rata, atau 0 bila array kosong.
 */
export function hitungRataRata(daftar) {
  if (daftar.length === 0) return 0;

  return daftar.reduce((jumlah, n) => jumlah + n, 0) / daftar.length;
}

/**
 * Mencari nilai tertinggi.
 * @param {number[]} daftar - Array angka.
 * @returns {number|null} Nilai tertinggi, atau null bila array kosong.
 */
export function cariTertinggi(daftar) {
  return daftar.length === 0 ? null : Math.max(...daftar);
}

/**
 * Mencari nilai terendah.
 * @param {number[]} daftar - Array angka.
 * @returns {number|null} Nilai terendah, atau null bila array kosong.
 */
export function cariTerendah(daftar) {
  return daftar.length === 0 ? null : Math.min(...daftar);
}

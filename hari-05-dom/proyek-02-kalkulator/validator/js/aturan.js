/**
 * Memeriksa nama.
 * @param {string} nilai - Isi kolom nama.
 * @returns {string} Pesan galat, atau '' bila valid (minimal 3 karakter tanpa spasi tepi).
 */
export function validasiNama(nilai) {
  return nilai.trim().length >= 3 ? "" : "Nama minimal 3 karakter.";
}

/**
 * Memeriksa format email.
 * @param {string} nilai - Isi kolom email.
 * @returns {string} Pesan galat, atau '' bila valid.
 */
export function validasiEmail(nilai) {
  const pola = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return pola.test(nilai) ? "" : "Format email tidak valid.";
}

/**
 * Memeriksa nomor HP Indonesia.
 * @param {string} nilai - Isi kolom nomor HP.
 * @returns {string} Pesan galat, atau '' bila valid (diawali 08, total 10-13 digit).
 */
export function validasiHp(nilai) {
  return /^08[0-9]{8,11}$/.test(nilai)
    ? ""
    : "Nomor HP diawali 08 dan 10-13 digit.";
}

/**
 * Memeriksa kekuatan kata sandi.
 * @param {string} nilai - Isi kolom kata sandi.
 * @returns {string} Pesan galat, atau '' bila valid (min. 8 karakter, ada huruf dan angka).
 */
export function validasiSandi(nilai) {
  const kuat =
    nilai.length >= 8 && /[0-9]/.test(nilai) && /[A-Za-z]/.test(nilai);
  return kuat ? "" : "Sandi minimal 8 karakter, kombinasi huruf dan angka.";
}

/**
 * Memeriksa konfirmasi kata sandi.
 * @param {string} nilai - Isi kolom konfirmasi.
 * @param {string} sandi - Isi kolom kata sandi.
 * @returns {string} Pesan galat, atau '' bila sama dengan sandi.
 */
export function validasiKonfirmasi(nilai, sandi) {
  return nilai === sandi ? "" : "Konfirmasi sandi tidak cocok.";
}

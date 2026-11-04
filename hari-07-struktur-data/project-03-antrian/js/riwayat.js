import { ErrorStrukturKosong, Stack } from './structures.js';

export const MAKS_RIWAYAT = 50;

function pastikanTeks(nilai) {
  if (typeof nilai !== 'string') {
    throw new TypeError('Teks catatan harus berupa string.');
  }
}

/** Logika undo/redo catatan memakai dua Stack (tanpa DOM). */
export class RiwayatTeks {
  #undoStack = new Stack(MAKS_RIWAYAT);
  #redoStack = new Stack(MAKS_RIWAYAT);
  #teks;

  /**
   * @param {string} [teksAwal] Isi catatan awal (tidak masuk riwayat).
   * @throws {TypeError} Jika teksAwal bukan string.
   */
  constructor(teksAwal = '') {
    pastikanTeks(teksAwal);
    this.#teks = teksAwal;
  }

  /**
   * Mencatat perubahan teks. Perubahan baru menghapus riwayat redo.
   * @param {string} teksBaru Isi catatan terbaru.
   * @returns {boolean} False bila teks sama dengan sebelumnya (diabaikan).
   * @throws {TypeError} Jika teksBaru bukan string.
   */
  ubah(teksBaru) {
    pastikanTeks(teksBaru);
    if (teksBaru === this.#teks) return false;
    this.#undoStack.push(this.#teks);
    this.#redoStack.clear();
    this.#teks = teksBaru;
    return true;
  }

  /**
   * Membatalkan perubahan terakhir.
   * @returns {string} Teks setelah undo.
   * @throws {ErrorStrukturKosong} Jika tidak ada langkah untuk dibatalkan.
   */
  undo() {
    if (!this.bisaUndo) {
      throw new ErrorStrukturKosong('Tidak ada langkah untuk dibatalkan.');
    }
    this.#redoStack.push(this.#teks);
    this.#teks = this.#undoStack.pop();
    return this.#teks;
  }

  /**
   * Mengulang perubahan yang dibatalkan.
   * @returns {string} Teks setelah redo.
   * @throws {ErrorStrukturKosong} Jika tidak ada langkah untuk diulang.
   */
  redo() {
    if (!this.bisaRedo) {
      throw new ErrorStrukturKosong('Tidak ada langkah untuk diulang.');
    }
    this.#undoStack.push(this.#teks);
    this.#teks = this.#redoStack.pop();
    return this.#teks;
  }

  /** @returns {string} Isi catatan saat ini. */
  get teks() {
    return this.#teks;
  }

  /** @returns {boolean} True jika undo tersedia. */
  get bisaUndo() {
    return !this.#undoStack.isEmpty();
  }

  /** @returns {boolean} True jika redo tersedia. */
  get bisaRedo() {
    return !this.#redoStack.isEmpty();
  }

  /** @returns {number} Jumlah langkah yang bisa di-undo (maks 50). */
  get jumlahLangkah() {
    return this.#undoStack.size;
  }
}

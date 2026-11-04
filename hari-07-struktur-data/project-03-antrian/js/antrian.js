import { ErrorStrukturKosong, LinkedQueue } from './structures.js';

export const AWALAN_NOMOR = 'A';
export const PANJANG_DIGIT_NOMOR = 3;
export const MAKS_RIWAYAT_PANGGILAN = 5;

const PESAN_ANTREAN_KOSONG =
  'Antrean kosong, tidak ada nomor yang bisa dipanggil.';

function formatNomor(urutan) {
  const digit = String(urutan).padStart(PANJANG_DIGIT_NOMOR, '0');
  return `${AWALAN_NOMOR}${digit}`;
}

/** Logika antrean loket klinik (tanpa DOM). */
export class Antrian {
  #antrean = new LinkedQueue();
  #urutanTerakhir = 0;
  #riwayatPanggilan = [];
  #nomorSekarang = null;

  /**
   * Mengeluarkan nomor antrean baru (A001, A002, ...).
   * @returns {string} Nomor yang baru diambil.
   */
  ambilNomor() {
    this.#urutanTerakhir += 1;
    const nomor = formatNomor(this.#urutanTerakhir);
    this.#antrean.enqueue(nomor);
    return nomor;
  }

  /**
   * Memanggil nomor terdepan (FIFO).
   * @returns {string} Nomor yang dipanggil.
   * @throws {ErrorStrukturKosong} Jika tidak ada yang menunggu.
   */
  panggilBerikutnya() {
    if (this.#antrean.isEmpty()) {
      throw new ErrorStrukturKosong(PESAN_ANTREAN_KOSONG);
    }
    const nomor = this.#antrean.dequeue();
    this.#nomorSekarang = nomor;
    this.#catatRiwayat(nomor);
    return nomor;
  }

  #catatRiwayat(nomor) {
    this.#riwayatPanggilan.unshift(nomor);
    if (this.#riwayatPanggilan.length > MAKS_RIWAYAT_PANGGILAN) {
      this.#riwayatPanggilan.pop();
    }
  }

  /** @returns {string|null} Nomor yang terakhir dipanggil, atau null. */
  get nomorSekarang() {
    return this.#nomorSekarang;
  }

  /** @returns {number} Jumlah nomor yang masih menunggu. */
  get jumlahMenunggu() {
    return this.#antrean.size;
  }

  /** @returns {string[]} Nomor yang menunggu, dari yang terdepan. */
  daftarMenunggu() {
    return this.#antrean.toArray();
  }

  /** @returns {string[]} Maksimal 5 nomor terakhir dipanggil, terbaru dulu. */
  riwayatPanggilan() {
    return [...this.#riwayatPanggilan];
  }

  /** Mengembalikan antrean ke kondisi awal (nomor mulai lagi dari A001). */
  reset() {
    this.#antrean.clear();
    this.#urutanTerakhir = 0;
    this.#riwayatPanggilan = [];
    this.#nomorSekarang = null;
  }
}

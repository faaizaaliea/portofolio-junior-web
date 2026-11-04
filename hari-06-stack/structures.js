/**
 * Struktur data Hari 6: Stack (LIFO) dan Queue (FIFO).
 *
 * Data internal memakai properti privat (#) sehingga tidak bisa diakses
 * langsung dari luar kelas; gunakan method yang tersedia.
 * Catatan: pop(), peek(), dequeue(), dan front() mengembalikan `undefined`
 * pada struktur kosong, jadi periksa isEmpty() bila `undefined` bisa menjadi
 * nilai yang sah.
 */

/** Stack (tumpukan): elemen terakhir yang masuk adalah yang pertama keluar. */
export class Stack {
  #items = [];
  #batas;

  /**
   * @param {number} [batas=Infinity] Jumlah elemen maksimum. Bila terlampaui,
   *   elemen paling bawah (paling lama) dibuang. Dipakai untuk membatasi
   *   riwayat undo.
   */
  constructor(batas = Infinity) {
    if (batas !== Infinity && (!Number.isInteger(batas) || batas < 1)) {
      throw new RangeError("batas harus bilangan bulat >= 1 atau Infinity");
    }
    this.#batas = batas;
  }

  /**
   * Menaruh elemen di puncak. O(1); O(n) hanya saat batas terlampaui.
   * @param {*} item
   */
  push(item) {
    this.#items.push(item);
    if (this.#items.length > this.#batas) {
      this.#items.shift();
    }
  }

  /**
   * Mengambil dan menghapus elemen puncak. O(1).
   * @returns {*} Elemen puncak, atau undefined bila kosong.
   */
  pop() {
    return this.isEmpty() ? undefined : this.#items.pop();
  }

  /**
   * Melihat elemen puncak tanpa menghapusnya. O(1).
   * @returns {*} Elemen puncak, atau undefined bila kosong.
   */
  peek() {
    return this.isEmpty() ? undefined : this.#items[this.#items.length - 1];
  }

  /** @returns {boolean} true bila tidak ada elemen. O(1). */
  isEmpty() {
    return this.#items.length === 0;
  }

  /** @returns {number} Jumlah elemen. O(1). */
  size() {
    return this.#items.length;
  }

  /** Mengosongkan stack. */
  clear() {
    this.#items = [];
  }
}

/**
 * Queue (antrean): elemen pertama yang masuk adalah yang pertama keluar.
 * Memakai objek dengan penanda head dan tail sehingga semua operasi O(1)
 * (berbeda dengan Array.shift yang O(n)).
 */
export class Queue {
  #items = {};
  #head = 0;
  #tail = 0;

  /**
   * Menambah elemen di belakang antrean. O(1).
   * @param {*} item
   */
  enqueue(item) {
    this.#items[this.#tail] = item;
    this.#tail++;
  }

  /**
   * Mengambil dan menghapus elemen terdepan. O(1).
   * @returns {*} Elemen terdepan, atau undefined bila kosong.
   */
  dequeue() {
    if (this.isEmpty()) {
      return undefined;
    }
    const item = this.#items[this.#head];
    delete this.#items[this.#head];
    this.#head++;
    return item;
  }

  /**
   * Melihat elemen terdepan tanpa menghapusnya. O(1).
   * @returns {*} Elemen terdepan, atau undefined bila kosong.
   */
  front() {
    return this.isEmpty() ? undefined : this.#items[this.#head];
  }

  /** @returns {boolean} true bila antrean kosong. O(1). */
  isEmpty() {
    return this.#tail === this.#head;
  }

  /** @returns {number} Jumlah elemen. O(1). */
  size() {
    return this.#tail - this.#head;
  }

  /** Mengosongkan antrean. */
  clear() {
    this.#items = {};
    this.#head = 0;
    this.#tail = 0;
  }

  /**
   * Salinan isi antrean dari depan ke belakang, untuk ditampilkan di UI
   * tanpa membuka properti internal. O(n).
   * @returns {Array} Array baru.
   */
  toArray() {
    const hasil = [];
    for (let i = this.#head; i < this.#tail; i++) {
      hasil.push(this.#items[i]);
    }
    return hasil;
  }
}

/**
 * Struktur data murni (tanpa DOM): Stack, LinkedList, dan LinkedQueue.
 * @module structures
 */

/** Galat yang dilempar saat operasi diminta pada struktur data kosong. */
export class ErrorStrukturKosong extends Error {
  /** @param {string} [pesan] Pesan galat yang mudah dipahami pengguna. */
  constructor(pesan = 'Struktur data kosong.') {
    super(pesan);
    this.name = 'ErrorStrukturKosong';
  }
}

/** Simpul tunggal pada linked list (detail internal, tidak diekspor). */
class Simpul {
  constructor(nilai) {
    this.nilai = nilai;
    this.berikutnya = null;
  }
}

/** Stack (LIFO) dengan kapasitas opsional; elemen terlama dibuang bila penuh. */
export class Stack {
  #items = [];
  #kapasitas;

  /**
   * @param {number} [kapasitas] Jumlah maksimal elemen (default tak terbatas).
   * @throws {RangeError} Jika kapasitas bukan bilangan lebih dari 0.
   */
  constructor(kapasitas = Number.POSITIVE_INFINITY) {
    if (!(kapasitas > 0)) {
      throw new RangeError('Kapasitas stack harus lebih dari 0.');
    }
    this.#kapasitas = kapasitas;
  }

  /**
   * Menaruh elemen di puncak stack. Jika melebihi kapasitas,
   * elemen paling bawah (terlama) dibuang.
   * @param {*} item Elemen yang ditambahkan.
   */
  push(item) {
    this.#items.push(item);
    if (this.#items.length > this.#kapasitas) {
      this.#items.shift();
    }
  }

  /**
   * Mengambil dan menghapus elemen di puncak stack.
   * @returns {*} Elemen teratas.
   * @throws {ErrorStrukturKosong} Jika stack kosong.
   */
  pop() {
    if (this.isEmpty()) {
      throw new ErrorStrukturKosong(
        'Stack kosong, tidak ada yang bisa diambil.',
      );
    }
    return this.#items.pop();
  }

  /**
   * Melihat elemen teratas tanpa menghapusnya.
   * @returns {*} Elemen teratas.
   * @throws {ErrorStrukturKosong} Jika stack kosong.
   */
  peek() {
    if (this.isEmpty()) {
      throw new ErrorStrukturKosong(
        'Stack kosong, tidak ada yang bisa dilihat.',
      );
    }
    return this.#items[this.#items.length - 1];
  }

  /** @returns {number} Jumlah elemen di stack. */
  get size() {
    return this.#items.length;
  }

  /** @returns {boolean} True jika stack kosong. */
  isEmpty() {
    return this.#items.length === 0;
  }

  /** Mengosongkan stack. */
  clear() {
    this.#items = [];
  }

  /** @returns {Array} Salinan elemen dari dasar ke puncak. */
  toArray() {
    return [...this.#items];
  }
}

/** Singly linked list dengan pointer head dan tail (append O(1)). */
export class LinkedList {
  #head = null;
  #tail = null;
  #size = 0;

  /**
   * Menambah nilai di akhir list. O(1).
   * @param {*} nilai Nilai yang ditambahkan.
   */
  append(nilai) {
    const simpul = new Simpul(nilai);
    if (this.#tail === null) {
      this.#head = simpul;
    } else {
      this.#tail.berikutnya = simpul;
    }
    this.#tail = simpul;
    this.#size += 1;
  }

  /**
   * Menambah nilai di awal list. O(1).
   * @param {*} nilai Nilai yang ditambahkan.
   */
  prepend(nilai) {
    const simpul = new Simpul(nilai);
    simpul.berikutnya = this.#head;
    this.#head = simpul;
    if (this.#tail === null) {
      this.#tail = simpul;
    }
    this.#size += 1;
  }

  /**
   * Menghapus dan mengembalikan nilai pertama. O(1).
   * @returns {*} Nilai pertama.
   * @throws {ErrorStrukturKosong} Jika list kosong.
   */
  removeFirst() {
    if (this.#head === null) {
      throw new ErrorStrukturKosong(
        'List kosong, tidak ada yang bisa dihapus.',
      );
    }
    const simpul = this.#head;
    this.#head = simpul.berikutnya;
    if (this.#head === null) {
      this.#tail = null;
    }
    this.#size -= 1;
    return simpul.nilai;
  }

  /**
   * Melihat nilai pertama tanpa menghapusnya.
   * @returns {*} Nilai pertama.
   * @throws {ErrorStrukturKosong} Jika list kosong.
   */
  peekFirst() {
    if (this.#head === null) {
      throw new ErrorStrukturKosong(
        'List kosong, tidak ada yang bisa dilihat.',
      );
    }
    return this.#head.nilai;
  }

  /** @returns {number} Jumlah nilai di list. */
  get size() {
    return this.#size;
  }

  /** @returns {boolean} True jika list kosong. */
  isEmpty() {
    return this.#size === 0;
  }

  /** Mengosongkan list. */
  clear() {
    this.#head = null;
    this.#tail = null;
    this.#size = 0;
  }

  /** @returns {Array} Salinan nilai dari awal ke akhir. */
  toArray() {
    const hasil = [];
    for (let simpul = this.#head; simpul !== null; simpul = simpul.berikutnya) {
      hasil.push(simpul.nilai);
    }
    return hasil;
  }
}

/** Queue (FIFO) berbasis LinkedList: enqueue dan dequeue sama-sama O(1). */
export class LinkedQueue {
  #list = new LinkedList();

  /**
   * Menambah elemen di belakang antrean. O(1).
   * @param {*} item Elemen yang ditambahkan.
   */
  enqueue(item) {
    this.#list.append(item);
  }

  /**
   * Mengambil elemen terdepan. O(1).
   * @returns {*} Elemen terdepan.
   * @throws {ErrorStrukturKosong} Jika antrean kosong.
   */
  dequeue() {
    if (this.#list.isEmpty()) {
      throw new ErrorStrukturKosong(
        'Queue kosong, tidak ada yang bisa diambil.',
      );
    }
    return this.#list.removeFirst();
  }

  /**
   * Melihat elemen terdepan tanpa menghapusnya.
   * @returns {*} Elemen terdepan.
   * @throws {ErrorStrukturKosong} Jika antrean kosong.
   */
  peek() {
    if (this.#list.isEmpty()) {
      throw new ErrorStrukturKosong(
        'Queue kosong, tidak ada yang bisa dilihat.',
      );
    }
    return this.#list.peekFirst();
  }

  /** @returns {number} Jumlah elemen di antrean. */
  get size() {
    return this.#list.size;
  }

  /** @returns {boolean} True jika antrean kosong. */
  isEmpty() {
    return this.#list.isEmpty();
  }

  /** Mengosongkan antrean. */
  clear() {
    this.#list.clear();
  }

  /** @returns {Array} Salinan elemen dari depan ke belakang. */
  toArray() {
    return this.#list.toArray();
  }
}

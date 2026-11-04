export class Stack {
  constructor() {
    this.data = [];
  }

  push(elemen) {
    this.data.push(elemen);
  }

  peek() {
    if (this.isEmpty()) {
      return undefined;
    }

    return this.data[this.data.length - 1];
  }

  pop() {
    if (this.isEmpty()) {
      return undefined;
    }

    return this.data.pop();
  }

  isEmpty() {
    return this.data.length === 0;
  }
}

export class Queue {
  constructor() {
    this.items = {};
    this.head = 0;
    this.tail = 0;
  }

  enqueue(item) {
    this.items[this.tail] = item;
    this.tail++;
  }

  dequeue() {
    if (this.isEmpty()) {
      return undefined;
    }

    const item = this.items[this.head];
    delete this.items[this.head];
    this.head++;

    return item;
  }

  front() {
    return this.items[this.head];
  }

  isEmpty() {
    return this.head === this.tail;
  }

  size() {
    return this.tail - this.head;
  }

  toArray() {
    const hasil = [];

    for (let i = this.head; i < this.tail; i++) {
      hasil.push(this.items[i]);
    }

    return hasil;
  }
}

class Node {
  constructor(data) {
    this.data = data;
    this.next = null;
  }
}

export class LinkedList {
  #head = null;
  #tail = null;
  #size = 0;

  addFirst(data) {
    const node = new Node(data);
    node.next = this.#head;
    this.#head = node;

    if (this.#tail === null) {
      this.#tail = node;
    }

    this.#size++;
  }

  addLast(data) {
    const node = new Node(data);

    if (this.#tail === null) {
      this.#head = node;
    } else {
      this.#tail.next = node;
    }

    this.#tail = node;
    this.#size++;
  }

  removeFirst() {
    if (this.#head === null) {
      return undefined;
    }

    const data = this.#head.data;
    this.#head = this.#head.next;

    if (this.#head === null) {
      this.#tail = null;
    }

    this.#size--;
    return data;
  }

  // Menghapus node pertama yang datanya cocok
  remove(data) {
    if (this.#head === null) {
      return false;
    }

    // Jika data ada di kepala
    if (this.#head.data === data) {
      this.removeFirst();
      return true;
    }

    let sebelumnya = this.#head;
    let saatIni = this.#head.next;

    while (saatIni !== null) {
      if (saatIni.data === data) {
        // Lewati node yang akan dihapus
        sebelumnya.next = saatIni.next;

        // Jika node yang dihapus adalah ekor
        if (saatIni === this.#tail) {
          this.#tail = sebelumnya;
        }

        this.#size--;
        return true;
      }

      sebelumnya = saatIni;
      saatIni = saatIni.next;
    }

    // Data tidak ditemukan
    return false;
  }

  peekFirst() {
    return this.#head === null ? undefined : this.#head.data;
  }

  find(predikat) {
    let saatIni = this.#head;

    while (saatIni !== null) {
      if (predikat(saatIni.data)) {
        return saatIni.data;
      }

      saatIni = saatIni.next;
    }

    return undefined;
  }

  size() {
    return this.#size;
  }

  isEmpty() {
    return this.#size === 0;
  }

  toArray() {
    const hasil = [];
    let saatIni = this.#head;

    while (saatIni !== null) {
      hasil.push(saatIni.data);
      saatIni = saatIni.next;
    }

    return hasil;
  }
}

// Queue berbasis Linked List: semua operasi O(1) dan detail internal tersembunyi
export class LinkedQueue {
  #daftar = new LinkedList();
  enqueue(item) {
    this.#daftar.addLast(item);
  }
  dequeue() {
    return this.#daftar.removeFirst();
  }
  front() {
    return this.#daftar.peekFirst();
  }
  isEmpty() {
    return this.#daftar.isEmpty();
  }
  size() {
    return this.#daftar.size();
  }
  toArray() {
    return this.#daftar.toArray();
  }
}

class BSTNode {
  constructor(data) {
    this.data = data;
    this.left = null;
    this.right = null;
  }
}

export class BinarySearchTree {
  constructor() {
    this.root = null;
  }

  insert(data) {
    const node = new BSTNode(data);

    if (this.root === null) {
      this.root = node;
      return;
    }

    let saatIni = this.root;

    while (true) {
      if (data === saatIni.data) {
        return;
      }

      if (data < saatIni.data) {
        if (saatIni.left === null) {
          saatIni.left = node;
          return;
        }

        saatIni = saatIni.left;
      } else {
        if (saatIni.right === null) {
          saatIni.right = node;
          return;
        }

        saatIni = saatIni.right;
      }
    }
  }

  has(data) {
    let saatIni = this.root;

    while (saatIni !== null) {
      if (data === saatIni.data) {
        return true;
      }

      if (data < saatIni.data) {
        saatIni = saatIni.left;
      } else {
        saatIni = saatIni.right;
      }
    }

    return false;
  }

  inOrder() {
    const hasil = [];

    function telusuri(node) {
      if (node === null) {
        return;
      }

      telusuri(node.left);
      hasil.push(node.data);
      telusuri(node.right);
    }

    telusuri(this.root);
    return hasil;
  }
}

export function bfs(graf, asal) {
  const dikunjungi = new Set();
  const antrean = [asal];
  const hasil = [];

  dikunjungi.add(asal);

  while (antrean.length > 0) {
    const kota = antrean.shift();
    hasil.push(kota);

    for (const tetangga of graf[kota] ?? []) {
      if (!dikunjungi.has(tetangga)) {
        dikunjungi.add(tetangga);
        antrean.push(tetangga);
      }
    }
  }

  return hasil;
}

export function dfs(graf, asal) {
  const dikunjungi = new Set();
  const hasil = [];

  function telusuri(kota) {
    dikunjungi.add(kota);
    hasil.push(kota);

    for (const tetangga of graf[kota] ?? []) {
      if (!dikunjungi.has(tetangga)) {
        telusuri(tetangga);
      }
    }
  }

  if (asal in graf) {
    telusuri(asal);
  }

  return hasil;
}

export function jarakTerpendek(graf, asal, tujuan) {
  if (!(asal in graf) || !(tujuan in graf)) {
    return -1;
  }

  const dikunjungi = new Set([asal]);
  const antrean = [[asal, 0]];

  while (antrean.length > 0) {
    const [kota, jarak] = antrean.shift();

    if (kota === tujuan) {
      return jarak;
    }

    for (const tetangga of graf[kota] ?? []) {
      if (!dikunjungi.has(tetangga)) {
        dikunjungi.add(tetangga);
        antrean.push([tetangga, jarak + 1]);
      }
    }
  }

  return -1;
}

export function cetakPohon(node, indentasi = "") {
  console.log(indentasi + node.nama);

  for (const anak of node.children ?? []) {
    cetakPohon(anak, indentasi + "  ");
  }
}

export function hitungNode(node) {
  let jumlah = 1;

  for (const anak of node.children ?? []) {
    jumlah += hitungNode(anak);
  }

  return jumlah;
}

export function cariNode(node, nama, jalur = "") {
  const jalurSekarang = jalur ? `${jalur}/${node.nama}` : node.nama;

  if (node.nama === nama) {
    return jalurSekarang;
  }

  for (const anak of node.children ?? []) {
    const hasil = cariNode(anak, nama, jalurSekarang);

    if (hasil !== null) {
      return hasil;
    }
  }

  return null;
}

export function cetakBST(node, prefix = "", posisi = "ROOT") {
  if (node === null) return;

  console.log(prefix + posisi + ": " + node.data);

  if (node.left !== null || node.right !== null) {
    if (node.left !== null) {
      cetakBST(node.left, prefix + "  ", "KIRI");
    }

    if (node.right !== null) {
      cetakBST(node.right, prefix + "  ", "KANAN");
    }
  }
}

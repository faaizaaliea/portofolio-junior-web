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

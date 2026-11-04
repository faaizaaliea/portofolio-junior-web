import { LinkedList, LinkedQueue } from "./structures.js";

// 1. Pengujian LinkedList
console.log("=== UJI LINKED LIST ===");

const daftar = new LinkedList();

console.log("Daftar kosong:", daftar.toArray());
console.log("Hapus saat kosong:", daftar.remove(10));

daftar.addFirst(20);
daftar.addFirst(10);
daftar.addLast(30);

console.log("Setelah add:", daftar.toArray());
// [10, 20, 30]

console.log(
  "Find 20:",
  daftar.find((x) => x === 20),
);
// 20

console.log("Hapus kepala:", daftar.remove(10));
// true
console.log(daftar.toArray());
// [20, 30]

console.log("Hapus tengah:", daftar.remove(30));
// true
console.log(daftar.toArray());
// [20]

daftar.addLast(30);
daftar.addLast(40);

console.log("Hapus tengah:", daftar.remove(30));
// true
console.log(daftar.toArray());
// [20, 40]

console.log("Hapus ekor:", daftar.remove(40));
// true
console.log(daftar.toArray());
// [20]

console.log("Hapus satu-satunya elemen:", daftar.remove(20));
// true
console.log("Sekarang kosong:", daftar.isEmpty());
// true

console.log("Hapus data tidak ada:", daftar.remove(99));
// false

// 2. Benchmark Queue
console.log("\n=== BENCHMARK QUEUE ===");

const JUMLAH = 100_000;

const queueArray = [];

console.time("Queue Array (shift)");

for (let i = 0; i < JUMLAH; i++) {
  queueArray.push(i);
}

while (queueArray.length > 0) {
  queueArray.shift();
}

console.timeEnd("Queue Array (shift)");

const queueLinked = new LinkedQueue();

console.time("LinkedQueue");

for (let i = 0; i < JUMLAH; i++) {
  queueLinked.enqueue(i);
}

while (!queueLinked.isEmpty()) {
  queueLinked.dequeue();
}

console.timeEnd("LinkedQueue");

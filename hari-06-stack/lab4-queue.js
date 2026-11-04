// Lab 4 - uji Queue (halaman antrean ada di folder antrian/).
import { Queue } from "./structures.js";

const antrian = new Queue();

antrian.enqueue("A001");
antrian.enqueue("A002");
antrian.enqueue("A003");

console.log("Isi antrean:", antrian.toArray());
console.log("Jumlah:", antrian.size());
console.log("Terdepan:", antrian.front());

console.log("Keluar:", antrian.dequeue());
console.log("Keluar:", antrian.dequeue());
console.log("Keluar:", antrian.dequeue());

console.log("Antrean kosong:", antrian.isEmpty());
console.log("Dequeue saat kosong:", antrian.dequeue()); // undefined, tidak error

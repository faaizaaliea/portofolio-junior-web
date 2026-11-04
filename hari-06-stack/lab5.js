// Lab 5 - mengukur efisiensi
import { Queue } from "./structures.js";

const N = 100000;
const angka = Array.from({ length: N }, (_, i) => i);
const himpunan = new Set(angka);

console.time("Array.includes x 1000");
for (let i = 0; i < 1000; i++) angka.includes(N - 1);
console.timeEnd("Array.includes x 1000");

console.time("Set.has x 1000");
for (let i = 0; i < 1000; i++) himpunan.has(N - 1);
console.timeEnd("Set.has x 1000");

const arr = Array.from({ length: N }, (_, i) => i);
console.time("Array.shift");
while (arr.length > 0) arr.shift();
console.timeEnd("Array.shift");

const q = new Queue();
for (let i = 0; i < N; i++) q.enqueue(i);
console.time("Queue.dequeue");
while (!q.isEmpty()) q.dequeue();
console.timeEnd("Queue.dequeue");

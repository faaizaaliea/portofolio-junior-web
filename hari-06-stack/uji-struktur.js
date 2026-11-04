// Uji otomatis Stack, Queue, dan algoritma.
import assert from "node:assert/strict";
import { Stack, Queue } from "./structures.js";
import { kurungSeimbang, balikString, isPalindrom } from "./algoritma.js";
import { frekuensiKata, adaDuplikat, irisan } from "./lab2.js";

let jumlah = 0;
function uji(nama, fungsi) {
  fungsi();
  jumlah++;
  console.log(`ok  ${nama}`);
}

uji("Stack kosong: pop, peek tidak error", () => {
  const s = new Stack();
  assert.equal(s.isEmpty(), true);
  assert.equal(s.size(), 0);
  assert.equal(s.pop(), undefined);
  assert.equal(s.peek(), undefined);
});

uji("Stack satu elemen", () => {
  const s = new Stack();
  s.push("A");
  assert.equal(s.size(), 1);
  assert.equal(s.peek(), "A");
  assert.equal(s.pop(), "A");
  assert.equal(s.isEmpty(), true);
});

uji("Stack LIFO dan clear", () => {
  const s = new Stack();
  ["A", "B", "C"].forEach((x) => s.push(x));
  assert.equal(s.peek(), "C");
  assert.deepEqual([s.pop(), s.pop(), s.pop()], ["C", "B", "A"]);
  s.push(1);
  s.clear();
  assert.equal(s.isEmpty(), true);
});

uji("Stack berbatas membuang yang terlama", () => {
  const s = new Stack(3);
  [1, 2, 3, 4, 5].forEach((x) => s.push(x));
  assert.equal(s.size(), 3);
  assert.deepEqual([s.pop(), s.pop(), s.pop()], [5, 4, 3]);
  assert.throws(() => new Stack(0), RangeError);
});

uji("Queue kosong: dequeue, front tidak error", () => {
  const q = new Queue();
  assert.equal(q.isEmpty(), true);
  assert.equal(q.size(), 0);
  assert.equal(q.dequeue(), undefined);
  assert.equal(q.front(), undefined);
  assert.deepEqual(q.toArray(), []);
});

uji("Queue satu elemen", () => {
  const q = new Queue();
  q.enqueue("A001");
  assert.equal(q.front(), "A001");
  assert.equal(q.size(), 1);
  assert.equal(q.dequeue(), "A001");
  assert.equal(q.isEmpty(), true);
});

uji("Queue FIFO, size = tail - head, dan bisa dipakai lagi", () => {
  const q = new Queue();
  ["A", "B", "C"].forEach((x) => q.enqueue(x));
  assert.equal(q.size(), 3);
  assert.equal(q.dequeue(), "A");
  assert.equal(q.size(), 2);
  assert.deepEqual(q.toArray(), ["B", "C"]);
  q.enqueue("D");
  assert.deepEqual([q.dequeue(), q.dequeue(), q.dequeue()], ["B", "C", "D"]);
  assert.equal(q.dequeue(), undefined);
  q.enqueue("E");
  assert.equal(q.front(), "E");
  q.clear();
  assert.equal(q.isEmpty(), true);
});

uji("Properti internal tidak terbuka dari luar kelas", () => {
  const s = new Stack();
  const q = new Queue();
  assert.equal(s.items, undefined);
  assert.equal(q.items, undefined);
  assert.equal(q.head, undefined);
  assert.equal(q.tail, undefined);
});

uji("kurungSeimbang", () => {
  assert.equal(kurungSeimbang("(a+b)*[c-d]"), true);
  assert.equal(kurungSeimbang("(a+b]"), false);
  assert.equal(kurungSeimbang("((a)"), false);
  assert.equal(kurungSeimbang(""), true);
  assert.equal(kurungSeimbang(")("), false);
  assert.equal(kurungSeimbang("{[()]}"), true);
});

uji("balikString dan isPalindrom", () => {
  assert.equal(balikString("STACK"), "KCATS");
  assert.equal(balikString(""), "");
  assert.equal(isPalindrom("katak"), true);
  assert.equal(isPalindrom("Kasur ini rusak"), true);
  assert.equal(isPalindrom("javascript"), false);
  assert.equal(isPalindrom(""), true);
});

uji("frekuensiKata menangani spasi ganda dan huruf besar", () => {
  assert.deepEqual(frekuensiKata("saya belajar web saya suka web"), [
    ["saya", 2],
    ["web", 2],
    ["belajar", 1],
    ["suka", 1],
  ]);
  assert.deepEqual(frekuensiKata("  Web   web "), [["web", 2]]);
  assert.deepEqual(frekuensiKata(""), []);
});

uji("adaDuplikat dan irisan", () => {
  assert.equal(adaDuplikat([]), false);
  assert.equal(adaDuplikat(["Sari"]), false);
  assert.equal(adaDuplikat(["Sari", "Budi", "Sari"]), true);
  assert.deepEqual(irisan(["a", "b", "c"], ["b", "c", "d"]), ["b", "c"]);
  assert.deepEqual(irisan([], ["a"]), []);
});

console.log(`\nSemua ${jumlah} uji lolos.`);

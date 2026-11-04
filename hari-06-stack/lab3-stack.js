// Lab 3 - Stack: uji dasar, kurung seimbang, balik string, palindrom.
import { Stack } from "./structures.js";
import { kurungSeimbang, balikString, isPalindrom } from "./algoritma.js";

console.log("=== UJI STACK ===");
const tumpukan = new Stack();
tumpukan.push("A");
tumpukan.push("B");
tumpukan.push("C");

console.log("Ukuran:", tumpukan.size()); // 3
console.log("Elemen teratas:", tumpukan.peek()); // C
console.log("Pop:", tumpukan.pop()); // C
console.log("Pop:", tumpukan.pop()); // B
console.log("Pop:", tumpukan.pop()); // A
console.log("Apakah kosong?", tumpukan.isEmpty()); // true
console.log("Pop saat kosong:", tumpukan.pop()); // undefined, tidak error

console.log("\n=== KURUNG SEIMBANG ===");
console.log(kurungSeimbang("(a+b)*[c-d]")); // true
console.log(kurungSeimbang("(a+b]")); // false
console.log(kurungSeimbang("((a)")); // false
console.log(kurungSeimbang("")); // true

console.log("\n=== BALIK STRING ===");
console.log(balikString("STACK")); // KCATS
console.log(balikString("Halo")); // olaH

console.log("\n=== PALINDROM ===");
console.log(isPalindrom("katak")); // true
console.log(isPalindrom("malam")); // true
console.log(isPalindrom("javascript")); // false
console.log(isPalindrom("Kasur ini rusak")); // true

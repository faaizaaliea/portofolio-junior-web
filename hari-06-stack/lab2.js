// Lab 2 - Map dan Set.
import { fileURLToPath } from "node:url";

/**
 * Memecah kalimat menjadi kata huruf kecil; spasi ganda dan tepi diabaikan.
 * @param {string} kalimat
 * @returns {string[]}
 */
export function pecahKata(kalimat) {
  return kalimat.toLowerCase().split(/\s+/).filter(Boolean);
}

/**
 * Frekuensi kata, terurut dari yang paling sering (seri: urut abjad). O(n).
 * @param {string} kalimat
 * @returns {Array<[string, number]>}
 */
export function frekuensiKata(kalimat) {
  const frekuensi = new Map();
  for (const kata of pecahKata(kalimat)) {
    frekuensi.set(kata, (frekuensi.get(kata) ?? 0) + 1);
  }
  return [...frekuensi.entries()].sort(
    (a, b) => b[1] - a[1] || a[0].localeCompare(b[0]),
  );
}

/**
 * Menghilangkan duplikat dengan Set (urutan kemunculan pertama terjaga).
 * @param {Array} daftar
 * @returns {Array}
 */
export function hilangkanDuplikat(daftar) {
  return [...new Set(daftar)];
}

/**
 * Irisan dua daftar. O(a + b).
 * @param {Array} a
 * @param {Array} b
 * @returns {Array} Elemen a yang juga ada di b.
 */
export function irisan(a, b) {
  const himpunanB = new Set(b);
  return [...new Set(a)].filter((x) => himpunanB.has(x));
}

/**
 * Apakah daftar berisi nilai ganda? O(n).
 * @param {Array} daftar
 * @returns {boolean}
 */
export function adaDuplikat(daftar) {
  return new Set(daftar).size !== daftar.length;
}

function main() {
  console.log("1. Frekuensi kata:");
  console.log(frekuensiKata("saya belajar web saya suka web"));

  const email = [
    "sari@gmail.com",
    "budi@gmail.com",
    "sari@gmail.com",
    "ani@gmail.com",
    "budi@gmail.com",
  ];
  console.log("2. Email sebelum:", email.length, "->", email);
  console.log(
    "   Email sesudah:",
    hilangkanDuplikat(email).length,
    "->",
    hilangkanDuplikat(email),
  );

  const kelasA = ["Sari", "Budi", "Ani", "Dina"];
  const kelasB = ["Budi", "Dina", "Rina", "Tono"];
  console.log("3. Irisan kelas A dan B:", irisan(kelasA, kelasB));

  console.log("4. adaDuplikat([]):", adaDuplikat([])); // false
  console.log("   adaDuplikat(['Sari']):", adaDuplikat(["Sari"])); // false
  console.log(
    "   adaDuplikat(['Sari','Budi','Sari']):",
    adaDuplikat(["Sari", "Budi", "Sari"]),
  ); // true
}

//  main() hanya bila berkas ini dieksekusi langsung (bukan diimpor).
if (process.argv[1] === fileURLToPath(import.meta.url)) {
  main();
}

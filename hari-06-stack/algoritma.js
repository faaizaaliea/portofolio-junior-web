import { Stack } from "./structures.js";

/**
 * Memeriksa apakah tanda kurung (), [], {} seimbang dan bersarang benar.
 * @param {string} teks
 * @returns {boolean}
 */
export function kurungSeimbang(teks) {
  const pasangan = { ")": "(", "]": "[", "}": "{" };
  const tumpukan = new Stack();

  for (const karakter of teks) {
    if ("([{".includes(karakter)) {
      tumpukan.push(karakter);
    } else if (karakter in pasangan) {
      if (tumpukan.isEmpty() || tumpukan.pop() !== pasangan[karakter]) {
        return false;
      }
    }
  }
  return tumpukan.isEmpty();
}

/**
 * Membalik string memakai Stack.
 * @param {string} teks
 * @returns {string}
 */
export function balikString(teks) {
  const tumpukan = new Stack();
  for (const karakter of teks) {
    tumpukan.push(karakter);
  }

  let hasil = "";
  while (!tumpukan.isEmpty()) {
    hasil += tumpukan.pop();
  }
  return hasil;
}

/**
 * Memeriksa palindrom; huruf besar/kecil, spasi, dan tanda baca diabaikan.
 * @param {string} teks
 * @returns {boolean}
 */
export function isPalindrom(teks) {
  const bersih = teks.toLowerCase().replace(/[^a-z0-9]/g, "");
  return bersih === balikString(bersih);
}

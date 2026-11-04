let angka = 9879;

// Cara 1: while, %, dan Math.floor()
let angkaSementara = angka;
let jumlahWhile = 0;

while (angkaSementara > 0) {
  let digit = angkaSementara % 10;
  jumlahWhile += digit;
  angkaSementara = Math.floor(angkaSementara / 10);
}

// Cara 2: Menggunakan indeks
let digitAngka = String(angka);
let jumlahIndex = 0;

for (let i = 0; i < digitAngka.length; i++) {
  jumlahIndex += Number(digitAngka[i]);
}

console.log("Hasil while:", jumlahWhile);
console.log("Hasil indeks:", jumlahIndex);
console.log("Apakah sama?", jumlahWhile === jumlahIndex);

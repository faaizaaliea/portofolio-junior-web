// 1. Segitiga bintang
for (let i = 1; i <= 7; i++) {
  let pola = "";

  for (let j = 1; j <= i; j++) {
    pola += "* ";
  }
  console.log(pola);
}

console.log("");

// 2. Piramida bintang
const TINGGI_PIRAMIDA = 5;
for (let baris = 1; baris <= TINGGI_PIRAMIDA; baris++) {
  const spasi = " ".repeat(TINGGI_PIRAMIDA - baris);
  const bintang = "*".repeat(2 * baris - 1);
  console.log(spasi + bintang);
}

console.log("");

// 3. Persegi angka
for (let a = 1; a <= 5; a++) {
  let baris = "";

  for (let b = 1; b <= 5; b++) {
    baris += b + " ";
  }
  console.log(baris);
}

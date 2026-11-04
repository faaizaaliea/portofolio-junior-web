// Lab 2 - Program A: konversi nilai ke huruf
const BATAS_A = 85;
const BATAS_B = 70;
const BATAS_C = 55;

const daftarNilai = [90, 85, 78, 70, 60, 55, 40];

for (let i = 0; i < daftarNilai.length; i++) {
  const nilai = daftarNilai[i];
  let grade;

  if (nilai >= BATAS_A) grade = "A";
  else if (nilai >= BATAS_B) grade = "B";
  else if (nilai >= BATAS_C) grade = "C";
  else grade = "D";

  console.log(`Nilai ${nilai} -> Grade: ${grade}`);
}

console.log("");

// Lab 2 - Program B: tahun kabisat
// Kabisat bila habis dibagi 4 tetapi tidak habis dibagi 100,
// atau habis dibagi 400.
const daftarTahun = [1900, 2000, 2023, 2024, 2100];

for (let i = 0; i < daftarTahun.length; i++) {
  const tahun = daftarTahun[i];
  const kabisat =
    (tahun % 4 === 0 && tahun % 100 !== 0) || tahun % 400 === 0;

  console.log(`${tahun}: ${kabisat ? "Kabisat" : "Bukan kabisat"}`);
}

// Lab 1 - Pengolah Nilai versi fungsi
const readline = require("readline/promises");
const { stdin: input, stdout: output } = require("process");

const NILAI_MIN = 0;
const NILAI_MAKS = 100;
const BATAS_LULUS = 70;
const BATAS_A = 85;
const BATAS_B = 70;
const BATAS_C = 55;

/**
 * Menghitung rata-rata dari daftar nilai.
 * @param {number[]} daftar - Array angka.
 * @returns {number} Rata-rata, atau 0 bila array kosong.
 */
function hitungRataRata(daftar) {
  if (daftar.length === 0) return 0;
  return daftar.reduce((jumlah, n) => jumlah + n, 0) / daftar.length;
}

/**
 * Mencari nilai tertinggi.
 * @param {number[]} daftar - Array angka.
 * @returns {number|null} Nilai tertinggi, atau null bila array kosong.
 */
function cariTertinggi(daftar) {
  return daftar.length === 0 ? null : Math.max(...daftar);
}

/**
 * Mencari nilai terendah.
 * @param {number[]} daftar - Array angka.
 * @returns {number|null} Nilai terendah, atau null bila array kosong.
 */
function cariTerendah(daftar) {
  return daftar.length === 0 ? null : Math.min(...daftar);
}

/**
 * Menentukan grade berdasarkan nilai siswa.
 * @param {number} nilai - Nilai siswa.
 * @returns {string} Grade A, B, C, atau D.
 */
function tentukanGrade(nilai) {
  if (nilai >= BATAS_A) return "A";
  if (nilai >= BATAS_B) return "B";
  if (nilai >= BATAS_C) return "C";
  return "D";
}

/**
 * Menentukan status kelulusan berdasarkan nilai siswa.
 * @param {number} nilai - Nilai siswa.
 * @returns {string} "Lulus" atau "Tidak lulus".
 */
function tentukanStatus(nilai) {
  return nilai >= BATAS_LULUS ? "Lulus" : "Tidak lulus";
}

/**
 * Mengubah teks masukan menjadi angka. Teks kosong atau hanya spasi dianggap
 * bukan angka, karena Number("") bernilai 0 dan akan lolos validasi.
 * @param {string} teks - Teks yang diketik pengguna.
 * @returns {number} Angka hasil konversi, atau NaN bila tidak valid.
 */
function ubahKeAngka(teks) {
  return teks.trim() === "" ? NaN : Number(teks);
}

/**
 * Memeriksa apakah nilai berada dalam rentang yang diizinkan.
 * @param {number} nilai - Nilai yang diperiksa.
 * @returns {boolean} true bila angka terbatas dan berada di 0-100.
 */
function nilaiValid(nilai) {
  return Number.isFinite(nilai) && nilai >= NILAI_MIN && nilai <= NILAI_MAKS;
}

/**
 * Menghitung jumlah siswa untuk setiap grade.
 * @param {number[]} daftarNilai - Array nilai siswa.
 * @returns {{A: number, B: number, C: number, D: number}} Jumlah per grade.
 */
function hitungJumlahGrade(daftarNilai) {
  const jumlah = { A: 0, B: 0, C: 0, D: 0 };
  for (const nilai of daftarNilai) {
    jumlah[tentukanGrade(nilai)]++;
  }
  return jumlah;
}

/**
 * Menghitung jumlah siswa yang lulus.
 * @param {number[]} daftarNilai - Array nilai siswa.
 * @returns {number} Banyak siswa dengan status "Lulus".
 */
function hitungJumlahLulus(daftarNilai) {
  return daftarNilai.filter((n) => tentukanStatus(n) === "Lulus").length;
}

/**
 * Mengurutkan data siswa dari nilai tertinggi (bubble sort manual).
 * Array asli tidak diubah; nama dan nilai ditukar bersamaan.
 * @param {string[]} daftarNama - Array nama siswa.
 * @param {number[]} daftarNilai - Array nilai siswa (sejajar dengan nama).
 * @returns {{nama: string[], nilai: number[]}} Salinan yang sudah terurut.
 */
function urutkanTurun(daftarNama, daftarNilai) {
  const nama = [...daftarNama];
  const nilai = [...daftarNilai];

  for (let i = 0; i < nilai.length - 1; i++) {
    for (let j = 0; j < nilai.length - 1 - i; j++) {
      if (nilai[j] < nilai[j + 1]) {
        [nilai[j], nilai[j + 1]] = [nilai[j + 1], nilai[j]];
        [nama[j], nama[j + 1]] = [nama[j + 1], nama[j]];
      }
    }
  }
  return { nama, nilai };
}

/**
 * Membuat satu baris histogram bintang.
 * @param {string} label - Label grade, mis. "A (>= 85)".
 * @param {number} jumlah - Banyak bintang.
 * @returns {string} Baris histogram.
 */
function buatBarisHistogram(label, jumlah) {
  return `${label} : ${"*".repeat(jumlah)} (${jumlah})`;
}

/** Menampilkan menu utama ke layar. */
function tampilkanMenu() {
  console.log("\n===== PENGOLAH NILAI =====");
  console.log("1. Input nilai siswa");
  console.log("2. Tampilkan statistik");
  console.log("3. Tampilkan predikat per siswa");
  console.log("4. Keluar");
}

/**
 * Menampilkan statistik kelas.
 * @param {string[]} nama - Array nama siswa.
 * @param {number[]} nilai - Array nilai siswa (sejajar dengan nama).
 */
function tampilkanStatistik(nama, nilai) {
  const tertinggi = cariTertinggi(nilai);
  const terendah = cariTerendah(nilai);
  const jumlahLulus = hitungJumlahLulus(nilai);

  console.log("\n--- STATISTIK KELAS ---");
  console.log(`Jumlah siswa      : ${nilai.length}`);
  console.log(`Rata-rata nilai   : ${hitungRataRata(nilai).toFixed(2)}`);
  console.log(
    `Nilai tertinggi   : ${tertinggi} (${nama[nilai.indexOf(tertinggi)]})`,
  );
  console.log(
    `Nilai terendah    : ${terendah} (${nama[nilai.indexOf(terendah)]})`,
  );
  console.log(`Siswa lulus       : ${jumlahLulus}`);
  console.log(`Siswa tidak lulus : ${nilai.length - jumlahLulus}`);
}

/**
 * Menampilkan histogram bintang per grade.
 * @param {number[]} nilai - Array nilai siswa.
 */
function tampilkanHistogram(nilai) {
  const jumlah = hitungJumlahGrade(nilai);

  console.log("\n--- HISTOGRAM GRADE ---");
  console.log(buatBarisHistogram(`A (>= ${BATAS_A})`, jumlah.A));
  console.log(buatBarisHistogram(`B (>= ${BATAS_B})`, jumlah.B));
  console.log(buatBarisHistogram(`C (>= ${BATAS_C})`, jumlah.C));
  console.log(buatBarisHistogram(`D (< ${BATAS_C})`, jumlah.D));
}

/**
 * Menampilkan laporan kelas: statistik dan histogram.
 * @param {string[]} nama - Array nama siswa.
 * @param {number[]} nilai - Array nilai siswa (sejajar dengan nama).
 */
function tampilkanLaporan(nama, nilai) {
  if (nilai.length === 0) {
    console.log("Belum ada data yang dimasukkan.");
    return;
  }
  tampilkanStatistik(nama, nilai);
  tampilkanHistogram(nilai);
}

/**
 * Menampilkan grade dan status tiap siswa, diurutkan dari nilai tertinggi.
 * @param {string[]} daftarNama - Array nama siswa.
 * @param {number[]} daftarNilai - Array nilai siswa (sejajar dengan nama).
 */
function tampilkanPredikat(daftarNama, daftarNilai) {
  if (daftarNilai.length === 0) {
    console.log("Belum ada data yang dimasukkan.");
    return;
  }
  const { nama, nilai } = urutkanTurun(daftarNama, daftarNilai);

  console.log("\n--- PREDIKAT SISWA (NILAI TERTINGGI DULU) ---");
  for (let i = 0; i < nilai.length; i++) {
    const grade = tentukanGrade(nilai[i]);
    const status = tentukanStatus(nilai[i]);
    console.log(
      `${i + 1}. ${nama[i]} | Nilai: ${nilai[i]} | Grade: ${grade} | ${status}`,
    );
  }
}

/**
 * Meminta nama dan nilai, memvalidasi, lalu menyimpannya bila valid.
 * @param {import("readline/promises").Interface} rl - Antarmuka readline.
 * @param {string[]} daftarNama - Array nama (diubah: ditambah bila valid).
 * @param {number[]} daftarNilai - Array nilai (diubah: ditambah bila valid).
 * @returns {Promise<void>}
 */
async function inputSiswa(rl, daftarNama, daftarNilai) {
  const nama = (await rl.question("Nama siswa: ")).trim();
  const nilai = ubahKeAngka(await rl.question("Nilai (0-100): "));

  if (nama === "" || !nilaiValid(nilai)) {
    console.log("Input tidak valid.");
    return;
  }
  daftarNama.push(nama);
  daftarNilai.push(nilai);
  console.log(`Data ${nama} dan ${nilai} berhasil ditambahkan.`);
}

/** Menguji fungsi murni; tidak mencetak apa pun bila semua benar. */
function ujiFungsi() {
  console.assert(hitungRataRata([80, 90]) === 85, "rata-rata 80,90 harus 85");
  console.assert(hitungRataRata([]) === 0, "rata-rata array kosong harus 0");
  console.assert(
    cariTertinggi([]) === null,
    "tertinggi array kosong harus null",
  );
  console.assert(cariTerendah([80, 65, 90]) === 65, "terendah harus 65");
  console.assert(tentukanGrade(85) === "A", "nilai 85 harus A");
  console.assert(tentukanGrade(69) === "C", "nilai 69 harus C");
  console.assert(tentukanGrade(54) === "D", "nilai 54 harus D");
  console.assert(tentukanStatus(70) === "Lulus", "nilai 70 harus Lulus");
  console.assert(nilaiValid(101) === false, "101 tidak valid");
  console.assert(nilaiValid(-1) === false, "-1 tidak valid");
  console.assert(nilaiValid(NaN) === false, "NaN tidak valid");
  console.assert(Number.isNaN(ubahKeAngka("")), "teks kosong bukan angka");
  console.assert(Number.isNaN(ubahKeAngka("   ")), "spasi bukan angka");
  console.assert(ubahKeAngka(" 85 ") === 85, "' 85 ' harus 85");
  console.assert(
    urutkanTurun(["a", "b"], [60, 90]).nama[0] === "b",
    "urutan salah",
  );
}

/** Menjalankan menu berulang sampai pengguna memilih keluar. */
async function main() {
  const rl = readline.createInterface({ input, output });
  const daftarNama = [];
  const daftarNilai = [];
  let jalan = true;

  while (jalan) {
    tampilkanMenu();
    const pilihan = (await rl.question("Pilih menu (1-4): ")).trim();

    switch (pilihan) {
      case "1":
        await inputSiswa(rl, daftarNama, daftarNilai);
        break;
      case "2":
        tampilkanLaporan(daftarNama, daftarNilai);
        break;
      case "3":
        tampilkanPredikat(daftarNama, daftarNilai);
        break;
      case "4":
        jalan = false;
        console.log("Terima kasih.");
        break;
      default:
        console.log("Pilihan tidak valid.");
    }
  }
  rl.close();
}

ujiFungsi();
main();

const readline = require("readline/promises");
const { stdin: input, stdout: output } = require("process");
const NILAI_MIN = 0;
const NILAI_MAKS = 100;
const BATAS_LULUS = 70;
function hitungRataRata(daftar) {
  if (daftar.length === 0) return 0;
  return daftar.reduce((jumlah, n) => jumlah + n, 0) / daftar.length;
}
function cariTertinggi(daftar) {
  return daftar.length === 0 ? null : Math.max(...daftar);
}
function cariTerendah(daftar) {
  return daftar.length === 0 ? null : Math.min(...daftar);
}
/**
 * Menentukan grade berdasarkan nilai siswa.
 * @param {number} nilai - Nilai siswa.
 * @returns {string} Grade siswa, yaitu A, B, C, atau D.
 */
function tentukanGrade(nilai) {
  if (nilai >= 85) return "A";
  if (nilai >= 70) return "B";
  if (nilai >= 55) return "C";
  return "D";
}
/**
 * Menentukan status berdasarkan nilai siswa.
 * @param {number} nilai - Nilai siswa.
 * @returns {string} lulus atau tidak lulus.
 */

function tentukanStatus(nilai) {
  return nilai >= BATAS_LULUS ? "Lulus" : "Tidak lulus";
}
function nilaiValid(nilai) {
  return Number.isFinite(nilai) && nilai >= NILAI_MIN && nilai <= NILAI_MAKS;
}
function ujiFungsi() {
  console.assert(hitungRataRata([80, 90]) === 85, "rata-rata 80,90 harus 85");
  console.assert(hitungRataRata([]) === 0, "rata-rata array kosong harus 0");
  console.assert(tentukanGrade(85) === "A", "nilai 85 harus A");
  console.assert(tentukanGrade(69) === "C", "nilai 69 harus C");
  console.assert(nilaiValid(101) === false, "101 tidak valid");
}
async function main() {
  const rl = readline.createInterface({ input, output });
  const daftarNama = [];
  const daftarNilai = [];
  let jalan = true;

  function tampilkanMenu() {
    console.log("\n===== PENGOLAH NILAI =====");
    console.log("1. Input nilai siswa");
    console.log("2. Tampilkan statistik");
    console.log("3. Tampilkan predikat per siswa");
    console.log("4. Keluar");
  }

  function tampilkanLaporan(nama, nilai) {
    if (nilai.length === 0) {
      console.log("Belum ada data yang dimasukkan.");
      return;
    }

    let tertinggi = nilai[0];
    let terendah = nilai[0];
    let siswaTertinggi = nama[0];
    let siswaTerendah = nama[0];

    let jumlahLulus = 0;
    let jumlahTidakLulus = 0;
    let countA = 0;
    let countB = 0;
    let countC = 0;
    let countD = 0;

    for (let i = 0; i < nilai.length; i++) {
      const nilaiSiswa = nilai[i];
      const namaSiswa = nama[i];

      if (nilaiSiswa > tertinggi) {
        tertinggi = nilaiSiswa;
        siswaTertinggi = namaSiswa;
      }

      if (nilaiSiswa < terendah) {
        terendah = nilaiSiswa;
        siswaTerendah = namaSiswa;
      }

      if (tentukanStatus(nilaiSiswa) === "Lulus") {
        jumlahLulus++;
      } else {
        jumlahTidakLulus++;
      }

      switch (tentukanGrade(nilaiSiswa)) {
        case "A":
          countA++;
          break;
        case "B":
          countB++;
          break;
        case "C":
          countC++;
          break;
        case "D":
          countD++;
          break;
      }
    }

    const rataRata = hitungRataRata(nilai).toFixed(2);

    console.log("\n--- STATISTIK KELAS ---");
    console.log(`Jumlah Siswa      : ${nilai.length}`);
    console.log(`Rata-rata Nilai   : ${rataRata}`);
    console.log(`Nilai Tertinggi   : ${tertinggi} (${siswaTertinggi})`);
    console.log(`Nilai Terendah    : ${terendah} (${siswaTerendah})`);
    console.log(`Siswa Lulus       : ${jumlahLulus}`);
    console.log(`Siswa Tidak Lulus : ${jumlahTidakLulus}`);

    console.log("\n--- HISTOGRAM GRADE ---");
    console.log(`A (85-100) : ${"*".repeat(countA)} (${countA})`);
    console.log(`B (70-84)  : ${"*".repeat(countB)} (${countB})`);
    console.log(`C (55-69)  : ${"*".repeat(countC)} (${countC})`);
    console.log(`D (<55)    : ${"*".repeat(countD)} (${countD})`);
  }
  while (jalan) {
    tampilkanMenu();
    const pilihan = await rl.question("Pilih menu (1-4): ");

    switch (pilihan) {
      case "1": {
        const nama = (await rl.question("Nama siswa: ")).trim();
        const nilai = Number(await rl.question("Nilai (0-100): "));

        if (nama === "" || !nilaiValid(nilai)) {
          console.log("Input tidak valid.");
        } else {
          daftarNama.push(nama);
          daftarNilai.push(nilai);
          console.log(`Data ${nama} dan ${nilai} berhasil ditambahkan`);
        }

        break;
      }

      case "2":
        tampilkanLaporan(daftarNama, daftarNilai);
        break;
      case "3": {
        if (daftarNama.length === 0) {
          console.log("⚠️ Belum ada data siswa yang tersimpan.");
          break;
        }

        // Menyalin array agar data asli tetap utuh sebelum diurutkan
        const namaSalinan = [...daftarNama];
        const nilaiSalinan = [...daftarNilai];
        const panjang = nilaiSalinan.length;

        // Tantangan Tambahan: Bubble Sort Manual (Urut dari nilai tertinggi ke terendah)
        for (let i = 0; i < panjang - 1; i++) {
          for (let j = 0; j < panjang - 1 - i; j++) {
            if (nilaiSalinan[j] < nilaiSalinan[j + 1]) {
              // Tukar posisi nilai
              let tempNilai = nilaiSalinan[j];
              nilaiSalinan[j] = nilaiSalinan[j + 1];
              nilaiSalinan[j + 1] = tempNilai;

              // Tukar posisi nama agar tetap sinkron
              let tempNama = namaSalinan[j];
              namaSalinan[j] = namaSalinan[j + 1];
              namaSalinan[j + 1] = tempNama;
            }
          }
        }

        console.log("\n--- DAFTAR PREDIKAT SISWA (TERURUT DARI TERTINGGI) ---");
        for (let i = 0; i < panjang; i++) {
          const nama = namaSalinan[i];
          const nilai = nilaiSalinan[i];

          // Penentuan grade sesuai aturan baru
          const grade = tentukanGrade(nilai);
          const status = tentukanStatus(nilai);
          console.log(
            `${i + 1}. ${nama} | Nilai: ${nilai} | Grade: ${grade} | Status: ${status}`,
          );
        }
        break;
      }
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

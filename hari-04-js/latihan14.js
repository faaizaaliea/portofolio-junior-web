const readline = require("readline/promises");
const { stdin: input, stdout: output } = require("process");

// Konstanta terpusat untuk menghindari magic number
const NILAI_MIN = 0;
const NILAI_MAKS = 100;
const BATAS_LULUS = 70;

async function main() {
  const rl = readline.createInterface({ input, output });
  const daftarNama = [];
  const daftarNilai = [];
  let jalan = true;

  // 1. Menu perulangan sampai pilihan 4
  while (jalan) {
    console.log("\n===== PENGOLAH NILAI SISWA =====");
    console.log("1. Input nilai siswa");
    console.log("2. Tampilkan statistik kelas");
    console.log("3. Tampilkan predikat & urutkan (Bubble Sort)");
    console.log("4. Keluar");

    const pilihan = await rl.question("Pilih menu (1-4): ");

    switch (pilihan) {
      case "1": {
        const namaInput = await rl.question("Nama siswa: ");
        const nama = namaInput.trim();
        const inputNilai = await rl.question("Nilai (0-100): ");
        const nilai = Number(inputNilai);

        // 2. Validasi input ketat (nama tidak kosong, angka valid, rentang 0-100)
        if (
          nama === "" ||
          Number.isNaN(nilai) ||
          nilai < NILAI_MIN ||
          nilai > NILAI_MAKS
        ) {
          console.log(
            "❌ Input tidak valid! Nama wajib diisi dan nilai harus berupa angka antara 0 - 100.",
          );
        } else {
          daftarNama.push(nama);
          daftarNilai.push(nilai);
          console.log(`✅ Data ${nama} (${nilai}) berhasil ditambahkan.`);
        }
        break;
      }

      case "2": {
        if (daftarNilai.length === 0) {
          console.log("⚠️ Belum ada data nilai yang tersimpan.");
          break;
        }

        let total = 0;
        let tertinggi = daftarNilai[0];
        let terendah = daftarNilai[0];
        let siswaTertinggi = daftarNama[0];
        let siswaTerendah = daftarNama[0];
        let jumlahLulus = 0;
        let jumlahTidakLulus = 0;

        // Inisialisasi penghitung histogram grade
        let countA = 0;
        let countB = 0;
        let countC = 0;
        let countD = 0;

        // 3 & 4. Perhitungan statistik, kelulusan, dan pengelompokan grade
        for (let i = 0; i < daftarNilai.length; i++) {
          const nilai = daftarNilai[i];
          const nama = daftarNama[i];

          total += nilai;

          if (nilai > tertinggi) {
            tertinggi = nilai;
            siswaTertinggi = nama;
          }

          if (nilai < terendah) {
            terendah = nilai;
            siswaTerendah = nama;
          }

          if (nilai >= BATAS_LULUS) {
            jumlahLulus++;
          } else {
            jumlahTidakLulus++;
          }

          // Penentuan grade untuk histogram
          if (nilai >= 85) {
            countA++;
          } else if (nilai >= 70) {
            countB++;
          } else if (nilai >= 55) {
            countC++;
          } else {
            countD++;
          }
        }

        // Rata-rata dua desimal
        const rataRata = (total / daftarNilai.length).toFixed(2);

        console.log("\n--- STATISTIK KELAS ---");
        console.log(`Jumlah Siswa      : ${daftarNilai.length}`);
        console.log(`Rata-rata Nilai   : ${rataRata}`);
        console.log(`Nilai Tertinggi   : ${tertinggi} (${siswaTertinggi})`);
        console.log(`Nilai Terendah    : ${terendah} (${siswaTerendah})`);
        console.log(`Siswa Lulus       : ${jumlahLulus}`);
        console.log(`Siswa Tidak Lulus : ${jumlahTidakLulus}`);

        // Histogram bintang per grade
        console.log("\n--- HISTOGRAM GRADE ---");
        console.log(`A (85-100) : ${"*".repeat(countA)} (${countA})`);
        console.log(`B (70-84)  : ${"*".repeat(countB)} (${countB})`);
        console.log(`C (55-69)  : ${"*".repeat(countC)} (${countC})`);
        console.log(`D (<55)    : ${"*".repeat(countD)} (${countD})`);
        break;
      }

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
          let grade = "D";
          if (nilai >= 85) {
            grade = "A";
          } else if (nilai >= 70) {
            grade = "B";
          } else if (nilai >= 55) {
            grade = "C";
          }

          const status = nilai >= BATAS_LULUS ? "Lulus" : "Tidak Lulus";

          console.log(
            `${i + 1}. ${nama} | Nilai: ${nilai} | Grade: ${grade} | Status: ${status}`,
          );
        }
        break;
      }

      case "4":
        jalan = false;
        console.log("Terima kasih telah menggunakan program pengolah nilai.");
        break;

      default:
        console.log(
          "❌ Pilihan menu tidak valid. Silakan masukkan angka 1 sampai 4.",
        );
    }
  }
  rl.close();
}

main();

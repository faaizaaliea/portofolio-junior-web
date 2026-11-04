// PBL Hari 4 - Aplikasi Konsol Pengolah Nilai
const readline = require("readline/promises");
const { stdin: input, stdout: output } = require("process");

// Konstanta terpusat untuk menghindari magic number
const NILAI_MIN = 0;
const NILAI_MAKS = 100;
const BATAS_LULUS = 70;
const BATAS_A = 85;
const BATAS_B = 70;
const BATAS_C = 55;

async function main() {
  const rl = readline.createInterface({ input, output });

  // Data disimpan dalam array paralel: indeks yang sama = siswa yang sama
  const daftarNama = [];
  const daftarNilai = [];
  const daftarGrade = [];
  let jalan = true;

  // Menu berulang sampai pengguna memilih 4
  while (jalan) {
    console.log("\n===== PENGOLAH NILAI =====");
    console.log("1. Input nilai siswa");
    console.log("2. Tampilkan rata-rata, nilai tertinggi, dan terendah");
    console.log("3. Tampilkan predikat lulus/tidak lulus per siswa");
    console.log("4. Keluar");

    const pilihan = (await rl.question("Pilih menu (1-4): ")).trim();

    switch (pilihan) {
      case "1": {
        const nama = (await rl.question("Nama siswa: ")).trim();
        const teksNilai = (await rl.question("Nilai (0-100): ")).trim();

        // Number("") bernilai 0, jadi input kosong dicek terpisah
        const nilai = Number(teksNilai);

        // Validasi: nama tidak kosong, nilai berupa angka dalam rentang 0-100
        if (
          nama === "" ||
          teksNilai === "" ||
          Number.isNaN(nilai) ||
          nilai < NILAI_MIN ||
          nilai > NILAI_MAKS
        ) {
          console.log(
            "❌ Input tidak valid. Nama wajib diisi dan nilai harus angka 0-100.",
          );
          break;
        }

        // Grade ditentukan sekali di sini agar tidak diulang di menu lain
        let grade;
        if (nilai >= BATAS_A) {
          grade = "A";
        } else if (nilai >= BATAS_B) {
          grade = "B";
        } else if (nilai >= BATAS_C) {
          grade = "C";
        } else {
          grade = "D";
        }

        daftarNama.push(nama);
        daftarNilai.push(nilai);
        daftarGrade.push(grade);
        console.log(`✅ Data ${nama} (${nilai}) berhasil ditambahkan.`);
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
        let jumlahA = 0;
        let jumlahB = 0;
        let jumlahC = 0;
        let jumlahD = 0;

        // Satu perulangan untuk semua statistik
        for (let i = 0; i < daftarNilai.length; i++) {
          const nilai = daftarNilai[i];

          total += nilai;

          if (nilai > tertinggi) {
            tertinggi = nilai;
            siswaTertinggi = daftarNama[i];
          }

          if (nilai < terendah) {
            terendah = nilai;
            siswaTerendah = daftarNama[i];
          }

          if (nilai >= BATAS_LULUS) {
            jumlahLulus++;
          } else {
            jumlahTidakLulus++;
          }

          switch (daftarGrade[i]) {
            case "A":
              jumlahA++;
              break;
            case "B":
              jumlahB++;
              break;
            case "C":
              jumlahC++;
              break;
            default:
              jumlahD++;
          }
        }

        const rataRata = (total / daftarNilai.length).toFixed(2);

        console.log("\n--- STATISTIK KELAS ---");
        console.log(`Jumlah siswa      : ${daftarNilai.length}`);
        console.log(`Rata-rata         : ${rataRata}`);
        console.log(`Nilai tertinggi   : ${tertinggi} (${siswaTertinggi})`);
        console.log(`Nilai terendah    : ${terendah} (${siswaTerendah})`);
        console.log(`Siswa lulus       : ${jumlahLulus}`);
        console.log(`Siswa tidak lulus : ${jumlahTidakLulus}`);

        // Tantangan tambahan: histogram bintang per grade
        console.log("\n--- HISTOGRAM GRADE ---");
        console.log(`A (>= ${BATAS_A}) : ${"*".repeat(jumlahA)} (${jumlahA})`);
        console.log(`B (>= ${BATAS_B}) : ${"*".repeat(jumlahB)} (${jumlahB})`);
        console.log(`C (>= ${BATAS_C}) : ${"*".repeat(jumlahC)} (${jumlahC})`);
        console.log(`D (< ${BATAS_C})  : ${"*".repeat(jumlahD)} (${jumlahD})`);
        break;
      }

      case "3": {
        if (daftarNama.length === 0) {
          console.log("⚠️ Belum ada data siswa yang tersimpan.");
          break;
        }

        // Salin array agar urutan data asli tidak berubah
        const namaUrut = [...daftarNama];
        const nilaiUrut = [...daftarNilai];
        const gradeUrut = [...daftarGrade];
        const jumlahSiswa = nilaiUrut.length;

        // Tantangan tambahan: bubble sort manual, nilai tertinggi lebih dulu
        for (let i = 0; i < jumlahSiswa - 1; i++) {
          for (let j = 0; j < jumlahSiswa - 1 - i; j++) {
            if (nilaiUrut[j] < nilaiUrut[j + 1]) {
              // Tukar ketiga array bersamaan agar tetap sinkron
              const sementaraNilai = nilaiUrut[j];
              nilaiUrut[j] = nilaiUrut[j + 1];
              nilaiUrut[j + 1] = sementaraNilai;

              const sementaraNama = namaUrut[j];
              namaUrut[j] = namaUrut[j + 1];
              namaUrut[j + 1] = sementaraNama;

              const sementaraGrade = gradeUrut[j];
              gradeUrut[j] = gradeUrut[j + 1];
              gradeUrut[j + 1] = sementaraGrade;
            }
          }
        }

        console.log("\n--- PREDIKAT SISWA (NILAI TERTINGGI DULU) ---");
        for (let i = 0; i < jumlahSiswa; i++) {
          const status = nilaiUrut[i] >= BATAS_LULUS ? "Lulus" : "Tidak lulus";
          console.log(
            `${i + 1}. ${namaUrut[i]} | Nilai: ${nilaiUrut[i]} | Grade: ${gradeUrut[i]} | ${status}`,
          );
        }
        break;
      }

      case "4":
        jalan = false;
        console.log("Terima kasih.");
        break;

      default:
        console.log("❌ Pilihan tidak valid. Masukkan angka 1 sampai 4.");
    }
  }

  rl.close();
}

main();

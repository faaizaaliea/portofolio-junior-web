import { hitungRataRata, cariTertinggi, cariTerendah } from "./matematika.js";

const inputNilai = document.getElementById("inputNilai");
const tombolHitung = document.getElementById("tombolHitung");

const hasilRataRata = document.getElementById("hasilRataRata");
const hasilTertinggi = document.getElementById("hasilTertinggi");
const hasilTerendah = document.getElementById("hasilTerendah");
const pesan = document.getElementById("pesan");

tombolHitung.addEventListener("click", function () {
  const teksNilai = inputNilai.value;

  // Memisahkan teks berdasarkan koma
  const bagianNilai = teksNilai.split(",");

  // Mengubah setiap bagian menjadi angka
  const daftarNilai = bagianNilai.map(function (teks) {
    return Number(teks.trim());
  });

  // Memeriksa apakah input valid
  const inputValid =
    teksNilai.trim() !== "" &&
    bagianNilai.every(function (teks) {
      return (
        teks.trim() !== "" &&
        Number.isFinite(Number(teks.trim())) &&
        Number(teks.trim()) >= 0 &&
        Number(teks.trim()) <= 100
      );
    });

  if (!inputValid) {
    pesan.textContent = "Masukkan angka 0-100 yang dipisahkan dengan koma.";
    hasilRataRata.textContent = "Rata-rata: -";
    hasilTertinggi.textContent = "Nilai tertinggi: -";
    hasilTerendah.textContent = "Nilai terendah: -";
    return;
  }

  // Memanggil fungsi dari matematika.js
  const rataRata = hitungRataRata(daftarNilai);
  const tertinggi = cariTertinggi(daftarNilai);
  const terendah = cariTerendah(daftarNilai);

  // Menampilkan hasil pada halaman
  hasilRataRata.textContent = "Rata-rata: " + rataRata.toFixed(2);

  hasilTertinggi.textContent = "Nilai tertinggi: " + tertinggi;

  hasilTerendah.textContent = "Nilai terendah: " + terendah;

  pesan.textContent = "";
});

import {
  validasiNama,
  validasiEmail,
  validasiHp,
  validasiSandi,
  validasiKonfirmasi,
} from "./aturan.js";

const form = document.getElementById("form-daftar");
const tombolKirim = document.getElementById("kirim");
const ringkasan = document.getElementById("ringkasan");

// Setiap aturan menerima nilai kolom dan mengembalikan pesan galat ('' bila valid)
const aturan = {
  nama: validasiNama,
  email: validasiEmail,
  hp: validasiHp,
  sandi: validasiSandi,
  konfirmasi: (nilai) => validasiKonfirmasi(nilai, form.elements.sandi.value),
};

/**
 * Menampilkan pesan galat di bawah kolom dan menandai kolom dengan aria-invalid.
 * @param {string} nama - Nama kolom (kunci pada objek aturan).
 * @param {string} pesan - Pesan galat; string kosong berarti valid.
 */
function tampilkanGalat(nama, pesan) {
  document.getElementById(`galat-${nama}`).textContent = pesan;
  form.elements[nama].setAttribute("aria-invalid", pesan ? "true" : "false");
}

/**
 * Memvalidasi satu kolom dan menampilkan hasilnya.
 * @param {string} nama - Nama kolom.
 * @returns {boolean} true bila kolom valid.
 */
function periksaField(nama) {
  const pesan = aturan[nama](form.elements[nama].value);
  tampilkanGalat(nama, pesan);
  return pesan === "";
}

/**
 * Memeriksa semua kolom tanpa menampilkan pesan.
 * @returns {boolean} true bila semua kolom valid.
 */
function semuaValid() {
  return Object.keys(aturan).every(
    (nama) => aturan[nama](form.elements[nama].value) === "",
  );
}

/** Menghapus semua pesan galat dan penanda aria-invalid. */
function bersihkanGalat() {
  Object.keys(aturan).forEach((nama) => {
    document.getElementById(`galat-${nama}`).textContent = "";
    form.elements[nama].removeAttribute("aria-invalid");
  });
}

form.addEventListener("input", (event) => {
  const nama = event.target.name;
  if (nama in aturan) periksaField(nama);
  if (nama === "sandi" && form.elements.konfirmasi.value !== "") {
    periksaField("konfirmasi");
  }
  tombolKirim.disabled = !semuaValid();
});

form.addEventListener("submit", (event) => {
  event.preventDefault();
  const hasil = Object.keys(aturan).map(periksaField);
  if (!hasil.every(Boolean)) return;

  // Ringkasan tanpa sandi, diisi dengan textContent (aman dari XSS)
  ringkasan.hidden = false;
  ringkasan.textContent =
    `Pendaftaran berhasil!\n` +
    `Nama: ${form.elements.nama.value.trim()}\n` +
    `Email: ${form.elements.email.value}\n` +
    `No. HP: ${form.elements.hp.value}`;

  form.reset();
  bersihkanGalat();
  tombolKirim.disabled = true;
});

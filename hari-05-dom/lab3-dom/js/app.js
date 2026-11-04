// ===== Counter =====
const angka = document.getElementById("angka");
let hitung = 0;

/** Menampilkan nilai counter ke halaman. */
function perbaruiAngka() {
  angka.textContent = hitung;
}

document.getElementById("tambah").addEventListener("click", () => {
  hitung++;
  perbaruiAngka();
});

document.getElementById("kurang").addEventListener("click", () => {
  hitung--;
  perbaruiAngka();
});

document.getElementById("reset").addEventListener("click", () => {
  hitung = 0;
  perbaruiAngka();
});

// ===== Daftar tugas =====
const MAKS_TEKS = 80;
const form = document.getElementById("form-tugas");
const kolomTeks = document.getElementById("teks-tugas");
const daftar = document.getElementById("daftar-tugas");
const sisa = document.getElementById("sisa");

/** @type {{teks: string, selesai: boolean}[]} */
const daftarTugas = [];

/**
 * Membuat satu elemen <li> untuk sebuah tugas. Teks dipasang dengan
 * textContent sehingga aman dari XSS.
 * @param {{teks: string, selesai: boolean}} tugas - Data tugas.
 * @param {number} indeks - Posisi tugas pada array daftarTugas.
 * @returns {HTMLLIElement} Elemen daftar yang siap ditambahkan.
 */
function buatItem(tugas, indeks) {
  const item = document.createElement("li");
  item.classList.toggle("selesai", tugas.selesai);

  const label = document.createElement("label");
  const centang = document.createElement("input");
  centang.type = "checkbox";
  centang.checked = tugas.selesai;
  centang.dataset.indeks = indeks;
  const teks = document.createElement("span");
  teks.textContent = tugas.teks;
  label.append(centang, " ", teks);

  const hapus = document.createElement("button");
  hapus.type = "button";
  hapus.className = "hapus";
  hapus.dataset.indeks = indeks;
  hapus.textContent = "Hapus";
  hapus.setAttribute("aria-label", `Hapus tugas ${tugas.teks}`);

  item.append(label, hapus);
  return item;
}

/** Menggambar ulang daftar tugas dan jumlah tugas yang tersisa. */
function tampilkanTugas() {
  daftar.replaceChildren(...daftarTugas.map(buatItem));
  const jumlahSisa = daftarTugas.filter((t) => !t.selesai).length;
  sisa.textContent = `Tugas tersisa: ${jumlahSisa}`;
}

/**
 * Menambahkan tugas baru bila teks tidak kosong dan tidak melebihi batas.
 * @param {string} teks - Teks tugas dari input.
 * @returns {boolean} true bila tugas berhasil ditambahkan.
 */
function tambahTugas(teks) {
  const bersih = teks.trim();
  if (bersih === "" || bersih.length > MAKS_TEKS) return false;
  daftarTugas.push({ teks: bersih, selesai: false });
  tampilkanTugas();
  return true;
}

form.addEventListener("submit", (event) => {
  event.preventDefault();
  if (tambahTugas(kolomTeks.value)) form.reset();
  kolomTeks.focus();
});

// Event delegation: satu pendengar pada daftar melayani semua item
daftar.addEventListener("change", (event) => {
  const indeks = event.target.dataset.indeks;
  if (indeks === undefined) return;
  daftarTugas[indeks].selesai = event.target.checked;
  tampilkanTugas();
});

daftar.addEventListener("click", (event) => {
  const tombol = event.target.closest("button.hapus");
  if (!tombol) return;
  daftarTugas.splice(Number(tombol.dataset.indeks), 1);
  tampilkanTugas();
});

perbaruiAngka();
tampilkanTugas();

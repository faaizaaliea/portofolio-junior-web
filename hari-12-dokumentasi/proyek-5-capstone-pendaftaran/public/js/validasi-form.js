/**
 * validasi-form.js  validasi sisi klien (UX cepat). Aturan HARUS sama dengan
 * src/validasi.php; server tetap sumber kebenaran.
 *
 * Form yang divalidasi ditandai atribut data-validasi="pendaftar" atau "kelas".
 */
(function () {
  "use strict";

  /** Aturan per bidang: fungsi mengembalikan pesan galat atau "" bila valid. */
  const aturan = {
    nama: (v) =>
      v.trim().length >= 3 && v.trim().length <= 100
        ? ""
        : "Nama 3–100 karakter.",
    email: (v) =>
      /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim())
        ? ""
        : "Format email tidak valid.",
    no_hp: (v) =>
      /^08[0-9]{8,11}$/.test(v.trim())
        ? ""
        : "Nomor HP diawali 08 dan 10-13 digit.",
    kelas_id: (v) => (v !== "" ? "" : "Pilih kelas yang tersedia."),
    nama_kelas: (v) =>
      v.trim().length >= 3 && v.trim().length <= 100
        ? ""
        : "Nama kelas 3–100 karakter.",
    kuota: (v) =>
      /^[0-9]+$/.test(v) && +v >= 1 && +v <= 1000 ? "" : "Kuota 1–1000.",
  };

  /**
   * Menampilkan/menghapus pesan galat pada satu bidang (kelas Bootstrap is-invalid).
   * @param {HTMLElement} input Elemen input/select/textarea.
   * @param {string} pesan Pesan galat; "" untuk menghapus.
   */
  function tampilkan(input, pesan) {
    input.classList.toggle("is-invalid", pesan !== "");
    let kotak = input.parentElement.querySelector(".invalid-feedback.js");
    if (pesan !== "") {
      if (!kotak) {
        kotak = document.createElement("div");
        kotak.className = "invalid-feedback js";
        input.parentElement.appendChild(kotak);
      }
      kotak.textContent = pesan;
    } else if (kotak) {
      kotak.remove();
    }
  }

  /**
   * Memeriksa satu bidang bila ada aturannya.
   * @param {HTMLElement} input
   * @returns {boolean} true bila valid.
   */
  function periksa(input) {
    const fungsi = aturan[input.name];
    if (!fungsi) return true;
    const pesan = fungsi(input.value);
    tampilkan(input, pesan);
    return pesan === "";
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("form[data-validasi]").forEach((form) => {
      const bidang = Array.from(
        form.querySelectorAll("input[name], select[name], textarea[name]"),
      );
      bidang.forEach((b) => b.addEventListener("blur", () => periksa(b)));
      form.addEventListener("submit", (event) => {
        const semuaValid = bidang.map(periksa).every(Boolean);
        if (!semuaValid) {
          event.preventDefault();
          const pertama = form.querySelector(".is-invalid");
          if (pertama) pertama.focus();
        }
      });
    });
  });
})();

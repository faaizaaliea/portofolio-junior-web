/**
 * app.js  perilaku umum halaman: toast flash (SweetAlert2), konfirmasi hapus,
 * dan inisialisasi DataTables + filter kelas pada tabel pendaftar.
 */
document.addEventListener("DOMContentLoaded", () => {
  const flash = document.getElementById("flash");
  if (flash) {
    Swal.fire({
      toast: true,
      position: "top-end",
      icon: "success",
      title: flash.dataset.pesan,
      showConfirmButton: false,
      timer: 3000,
    });
  }

  // Konfirmasi hapus untuk setiap form ber-kelas .form-hapus (pendaftar & kelas).
  document.querySelectorAll(".form-hapus").forEach((form) => {
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      Swal.fire({
        title: "Hapus data?",
        text: `Data ${form.dataset.nama} akan dihapus permanen.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Batal",
      }).then((hasil) => {
        if (hasil.isConfirmed) form.submit();
      });
    });
  });

  const tabel = document.getElementById("tabelPendaftar");
  if (tabel && window.jQuery) {
    const dt = window.jQuery(tabel).DataTable({
      pageLength: 10,
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json",
      },
      columnDefs: [{ orderable: false, targets: -1 }],
    });

    // Filter kelas: mencari persis pada kolom ke-4 (indeks 3).
    const filter = document.getElementById("filterKelas");
    if (filter) {
      filter.addEventListener("change", () => {
        const nilai = filter.value.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
        dt.column(3)
          .search(nilai ? `^${nilai}$` : "", true, false)
          .draw();
      });
    }
  }
});

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
    window.jQuery(tabel).DataTable({
      pageLength: 10,
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json",
      },
      columnDefs: [{ orderable: false, targets: -1 }],
    });
  }
});

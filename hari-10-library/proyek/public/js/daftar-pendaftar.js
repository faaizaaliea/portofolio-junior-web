$(function () {
  $("#tabelPendaftar").DataTable({
    pageLength: 10,
    order: [],
    columnDefs: [{ targets: -1, orderable: false, searchable: false }],
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data",
      info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
      infoEmpty: "Tidak ada data",
      infoFiltered: "(disaring dari _MAX_ data)",
      zeroRecords: "Tidak ada data yang cocok",
      emptyTable: "Belum ada pendaftar",
      paginate: {
        first: "Awal",
        last: "Akhir",
        next: "Berikutnya",
        previous: "Sebelumnya",
      },
    },
  });

  // Delegasi pada tabel: baris di halaman lain dimuat ulang DataTables, jadi pasang pada induknya.
  $("#tabelPendaftar").on("submit", "form.form-hapus", function (e) {
    e.preventDefault();
    const form = this;
    const nama = form.dataset.nama || "ini";
    Swal.fire({
      title: "Hapus data?",
      text: 'Data "' + nama + '" akan dihapus permanen.', // text (bukan html) agar aman dari XSS
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Ya, hapus",
      cancelButtonText: "Batal",
    }).then(function (hasil) {
      if (hasil.isConfirmed) {
        form.submit();
      }
    });
  });
});

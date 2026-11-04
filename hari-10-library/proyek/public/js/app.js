// Notifikasi flash (toast) untuk semua halaman.
// DataTables dan konfirmasi hapus ada di js/daftar-pendaftar.js.
document.addEventListener("DOMContentLoaded", () => {
  const flash = document.getElementById("flash");
  if (flash && window.Swal) {
    Swal.fire({
      toast: true,
      position: "top-end",
      icon: "success",
      title: flash.dataset.pesan,
      showConfirmButton: false,
      timer: 3000,
    });
  }
});

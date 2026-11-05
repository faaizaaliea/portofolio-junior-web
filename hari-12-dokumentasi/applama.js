/**
 * Script utama untuk menangani interaksi UI pada sisi klien.
 * Dijalankan setelah seluruh struktur DOM (HTML) selesai dimuat.
 */
document.addEventListener('DOMContentLoaded', () => {
  
  // ---------------------------------------------------------
  // 1. Notifikasi Flash (Toast)
  // ---------------------------------------------------------
  
  /** 
   * Elemen HTML untuk notifikasi flash.
   * @type {HTMLElement | null} 
   */
  const flash = document.getElementById('flash');
  
  if (flash) {
    /** 
     * Konfigurasi notifikasi toast SweetAlert2.
     * @type {Object} 
     */
    const toastConfig = {
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: flash.dataset.pesan,
      showConfirmButton: false,
      timer: 3000
    };
    Swal.fire(toastConfig);
  }

  // ---------------------------------------------------------
  // 2. Konfirmasi Hapus Data
  // ---------------------------------------------------------
  
  /** 
   * Daftar formulir yang membutuhkan konfirmasi sebelum dihapus.
   * @type {NodeListOf<HTMLFormElement>} 
   */
  const formHapus = document.querySelectorAll('.form-hapus');
  
  formHapus.forEach((form) => {
    /**
     * Menghentikan submit bawaan dan menampilkan konfirmasi.
     * @param {Event} event - Objek event dari aksi submit
     */
    form.addEventListener('submit', (event) => {
      event.preventDefault(); 
      
      Swal.fire({
        title: 'Hapus data?',
        text: `Data ${form.dataset.nama} akan dihapus permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
      }).then((hasil) => {
        if (hasil.isConfirmed) {
            form.submit();
        }
      });
    });
  });

  // ---------------------------------------------------------
  // 3. Inisialisasi DataTables (Lab 4)
  // ---------------------------------------------------------
  
  /** 
   * Elemen tabel yang akan diubah menjadi DataTables.
   * @type {HTMLElement | null} 
   */
  const tabel = document.getElementById('tabelPendaftar');
  
  if (tabel && window.jQuery) {
    /**
     * Konfigurasi DataTables bahasa Indonesia.
     * @type {Object}
     */
    const dtConfig = {
      pageLength: 10,
      language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' },
      columnDefs: [{ orderable: false, targets: -1 }] 
    };
    
    window.jQuery(tabel).DataTable(dtConfig);
  }
});
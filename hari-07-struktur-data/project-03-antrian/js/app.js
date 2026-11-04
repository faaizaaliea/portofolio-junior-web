import { Antrian } from './antrian.js';
import { MAKS_RIWAYAT, RiwayatTeks } from './riwayat.js';
import { ErrorStrukturKosong } from './structures.js';

const KUNCI_CATATAN = 'klinik.catatan-terakhir';
const NOMOR_KOSONG = '-';
const PESAN_KONFIRMASI_RESET = 'Reset antrean? Semua nomor akan dihapus.';
const PESAN_DAFTAR_KOSONG = 'Tidak ada yang menunggu';
const PESAN_RIWAYAT_KOSONG = 'Belum ada panggilan';

const ambil = (id) => document.getElementById(id);
const elemen = {
  nomorDipanggil: ambil('nomor-dipanggil'),
  pesanAntrian: ambil('pesan-antrian'),
  jumlahMenunggu: ambil('jumlah-menunggu'),
  daftarMenunggu: ambil('daftar-menunggu'),
  riwayatPanggilan: ambil('riwayat-panggilan'),
  catatan: ambil('catatan'),
  tombolUndo: ambil('undo'),
  tombolRedo: ambil('redo'),
  penghitung: ambil('penghitung-riwayat'),
  statusSimpan: ambil('status-simpan'),
};

const antrian = new Antrian();

function muatCatatan() {
  try {
    return localStorage.getItem(KUNCI_CATATAN) ?? '';
  } catch {
    return '';
  }
}

const riwayat = new RiwayatTeks(muatCatatan());

function simpanCatatan() {
  try {
    localStorage.setItem(KUNCI_CATATAN, riwayat.teks);
    elemen.statusSimpan.textContent = 'Tersimpan otomatis.';
  } catch {
    elemen.statusSimpan.textContent = 'Gagal menyimpan catatan.';
  }
}

function isiDaftar(daftarEl, isi, teksKosong) {
  const teksItem = isi.length > 0 ? isi : [teksKosong];
  const items = teksItem.map((teks) => {
    const li = document.createElement('li');
    li.textContent = teks;
    return li;
  });
  daftarEl.replaceChildren(...items);
}

function renderAntrian() {
  elemen.nomorDipanggil.textContent = antrian.nomorSekarang ?? NOMOR_KOSONG;
  elemen.jumlahMenunggu.textContent = String(antrian.jumlahMenunggu);
  isiDaftar(
    elemen.daftarMenunggu,
    antrian.daftarMenunggu(),
    PESAN_DAFTAR_KOSONG,
  );
  isiDaftar(
    elemen.riwayatPanggilan,
    antrian.riwayatPanggilan(),
    PESAN_RIWAYAT_KOSONG,
  );
}

function renderTombolRiwayat() {
  elemen.tombolUndo.disabled = !riwayat.bisaUndo;
  elemen.tombolRedo.disabled = !riwayat.bisaRedo;
  elemen.penghitung.textContent = `Riwayat: ${riwayat.jumlahLangkah}/${MAKS_RIWAYAT} langkah`;
}

function tampilkanPesan(teks) {
  elemen.pesanAntrian.textContent = teks;
}

function tampilkanStatusCatatan(teks) {
  elemen.statusSimpan.textContent = teks;
}

function tanganiGalat(error, tampilkan = tampilkanPesan) {
  if (!(error instanceof ErrorStrukturKosong)) throw error;
  tampilkan(error.message);
}

function tanganiAmbil() {
  const nomor = antrian.ambilNomor();
  tampilkanPesan(`Nomor ${nomor} berhasil diambil.`);
  renderAntrian();
}

function tanganiPanggil() {
  try {
    const nomor = antrian.panggilBerikutnya();
    tampilkanPesan(`Memanggil nomor ${nomor}.`);
  } catch (error) {
    tanganiGalat(error);
  }
  renderAntrian();
}

function tanganiResetAntrian() {
  if (!window.confirm(PESAN_KONFIRMASI_RESET)) return;
  antrian.reset();
  tampilkanPesan('Antrean telah direset.');
  renderAntrian();
}

function tanganiKetik() {
  riwayat.ubah(elemen.catatan.value);
  simpanCatatan();
  renderTombolRiwayat();
}

function jalankanLangkah(aksi) {
  try {
    elemen.catatan.value = aksi();
    simpanCatatan();
  } catch (error) {
    tanganiGalat(error, tampilkanStatusCatatan);
  }
  renderTombolRiwayat();
}

ambil('ambil').addEventListener('click', tanganiAmbil);
ambil('panggil').addEventListener('click', tanganiPanggil);
ambil('reset-antrian').addEventListener('click', tanganiResetAntrian);
elemen.catatan.addEventListener('input', tanganiKetik);
elemen.tombolUndo.addEventListener('click', () =>
  jalankanLangkah(() => riwayat.undo()),
);
elemen.tombolRedo.addEventListener('click', () =>
  jalankanLangkah(() => riwayat.redo()),
);

elemen.catatan.value = riwayat.teks;
renderAntrian();
renderTombolRiwayat();

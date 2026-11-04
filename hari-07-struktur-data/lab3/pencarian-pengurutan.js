// Lab 3 - Pencarian dan Pengurutan
// Jalankan: node pencarian-pengurutan.js
import { performance } from 'node:perf_hooks';

const UKURAN_PERBANDINGAN = 1000;
const UKURAN_BENCHMARK_KECIL = 10000;
const UKURAN_BENCHMARK_BESAR = 100000;
const NILAI_MAKS_ACAK = 100000;
const BENIH_ACAK = 2026;
const DESIMAL_WAKTU = 2;

// ---------------------------------------------------------------
// Pencarian
// ---------------------------------------------------------------

// Pencarian linear: O(n), tidak perlu terurut
function cariLinear(daftar, target) {
  for (let i = 0; i < daftar.length; i++) {
    if (daftar[i] === target) return i;
  }
  return -1;
}

// Pencarian biner: O(log n), WAJIB terurut
function cariBiner(daftarUrut, target) {
  let kiri = 0;
  let kanan = daftarUrut.length - 1;
  while (kiri <= kanan) {
    const tengah = Math.floor((kiri + kanan) / 2);
    if (daftarUrut[tengah] === target) return tengah;
    if (daftarUrut[tengah] < target) kiri = tengah + 1;
    else kanan = tengah - 1;
  }
  return -1;
}

// ---------------------------------------------------------------
// Pengurutan (versi dengan penghitung perbandingan)
// ---------------------------------------------------------------

// Bubble sort: O(n^2), O(n) bila sudah terurut (berkat penanda tukar)
function bubbleSortHitung(daftar) {
  const hasil = [...daftar];
  let perbandingan = 0;

  for (let i = 0; i < hasil.length - 1; i++) {
    let adaTukar = false;
    for (let j = 0; j < hasil.length - 1 - i; j++) {
      perbandingan++;
      if (hasil[j] > hasil[j + 1]) {
        [hasil[j], hasil[j + 1]] = [hasil[j + 1], hasil[j]];
        adaTukar = true;
      }
    }
    if (!adaTukar) break;
  }

  return { hasil, perbandingan };
}

// Insertion sort: O(n^2), O(n) untuk data hampir/sudah terurut
function insertionSortHitung(daftar) {
  const hasil = [...daftar];
  let perbandingan = 0;

  for (let i = 1; i < hasil.length; i++) {
    const nilaiSekarang = hasil[i];
    let j = i - 1;
    while (j >= 0) {
      perbandingan++;
      if (hasil[j] <= nilaiSekarang) break;
      hasil[j + 1] = hasil[j];
      j--;
    }
    hasil[j + 1] = nilaiSekarang;
  }

  return { hasil, perbandingan };
}

const bubbleSort = (daftar) => bubbleSortHitung(daftar).hasil;
const insertionSort = (daftar) => insertionSortHitung(daftar).hasil;

// Merge sort: O(n log n), membagi lalu menggabungkan
function mergeSort(daftar) {
  if (daftar.length <= 1) return daftar;
  const tengah = Math.floor(daftar.length / 2);
  return gabung(
    mergeSort(daftar.slice(0, tengah)),
    mergeSort(daftar.slice(tengah)),
  );
}

function gabung(kiri, kanan) {
  const hasil = [];
  let i = 0;
  let j = 0;
  while (i < kiri.length && j < kanan.length) {
    hasil.push(kiri[i] <= kanan[j] ? kiri[i++] : kanan[j++]);
  }
  return hasil.concat(kiri.slice(i), kanan.slice(j));
}

const urutBawaan = (daftar) => [...daftar].sort((a, b) => a - b);

// ---------------------------------------------------------------
// Pembuat data (acak berbenih agar hasil bisa diulang)
// ---------------------------------------------------------------

function buatPengacak(benih) {
  let keadaan = benih;
  return () => {
    keadaan = (keadaan + 0x6d2b79f5) | 0;
    let t = Math.imul(keadaan ^ (keadaan >>> 15), 1 | keadaan);
    t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
    return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
  };
}

function buatDataAcak(jumlah, pengacak) {
  return Array.from({ length: jumlah }, () =>
    Math.floor(pengacak() * NILAI_MAKS_ACAK),
  );
}

const buatDataTerurut = (jumlah) => Array.from({ length: jumlah }, (_, i) => i);
const buatDataTerbalik = (jumlah) => buatDataTerurut(jumlah).reverse();

// ---------------------------------------------------------------
// 1. Uji kebenaran (kosong, satu elemen, terurut, terbalik, duplikat)
// ---------------------------------------------------------------

let jumlahGagal = 0;

function periksa(keterangan, aktual, harapan) {
  const cocok = JSON.stringify(aktual) === JSON.stringify(harapan);
  if (!cocok) jumlahGagal++;
  console.log(`${cocok ? 'LULUS' : 'GAGAL'}  ${keterangan}`);
}

function ujiKebenaran() {
  const kasus = {
    kosong: [],
    'satu elemen': [5],
    'sudah terurut': [1, 2, 3, 4],
    terbalik: [4, 3, 2, 1],
    'berisi duplikat': [3, 1, 3, 2, 1],
  };
  const pengurut = { bubbleSort, insertionSort, mergeSort, urutBawaan };

  console.log('=== 1a. UJI PENGURUTAN ===');
  for (const [namaKasus, data] of Object.entries(kasus)) {
    const harapan = urutBawaan(data);
    for (const [nama, fungsi] of Object.entries(pengurut)) {
      periksa(`${nama} - ${namaKasus}`, fungsi(data), harapan);
    }
  }

  console.log('\n=== 1b. UJI PENCARIAN ===');
  const acak = [7, 3, 9, 1, 5];
  const urut = urutBawaan(acak);
  periksa('cariLinear 9 (ada)', cariLinear(acak, 9), 2);
  periksa('cariLinear 8 (tidak ada)', cariLinear(acak, 8), -1);
  periksa('cariLinear pada array kosong', cariLinear([], 1), -1);
  periksa('cariBiner 9 pada data terurut', cariBiner(urut, 9), 4);
  periksa('cariBiner 8 (tidak ada)', cariBiner(urut, 8), -1);
  periksa('cariBiner pada array kosong', cariBiner([], 1), -1);
  periksa('cariBiner satu elemen', cariBiner([5], 5), 0);
}

// ---------------------------------------------------------------
// 2. Perbandingan jumlah perbandingan: bubble vs insertion (1.000 data)
// ---------------------------------------------------------------

function bandingkanPerbandingan() {
  const pengacak = buatPengacak(BENIH_ACAK);
  const skenario = {
    acak: buatDataAcak(UKURAN_PERBANDINGAN, pengacak),
    terurut: buatDataTerurut(UKURAN_PERBANDINGAN),
    terbalik: buatDataTerbalik(UKURAN_PERBANDINGAN),
  };

  console.log(`\n=== 2. JUMLAH PERBANDINGAN (${UKURAN_PERBANDINGAN} data) ===`);
  const baris = Object.entries(skenario).map(([nama, data]) => ({
    data: nama,
    bubble: bubbleSortHitung(data).perbandingan,
    insertion: insertionSortHitung(data).perbandingan,
  }));
  console.table(baris);
}

// ---------------------------------------------------------------
// 3. Benchmark waktu: bubble vs merge vs Array.sort
// ---------------------------------------------------------------

function ukurWaktu(fungsi, data) {
  const mulai = performance.now();
  fungsi(data);
  return performance.now() - mulai;
}

function benchmark(jumlah, sertakanBubble) {
  const data = buatDataAcak(jumlah, buatPengacak(BENIH_ACAK));
  const hasil = {
    mergeSort: ukurWaktu(mergeSort, data),
    'Array.sort': ukurWaktu(urutBawaan, data),
  };
  if (sertakanBubble) hasil.bubbleSort = ukurWaktu(bubbleSort, data);

  console.log(`\nJumlah data: ${jumlah.toLocaleString('id-ID')}`);
  for (const [nama, ms] of Object.entries(hasil)) {
    console.log(`  ${nama.padEnd(11)}: ${ms.toFixed(DESIMAL_WAKTU)} ms`);
  }
  if (!sertakanBubble) {
    console.log('  bubbleSort : dilewati (O(n^2), terlalu lama)');
  }
}

ujiKebenaran();
bandingkanPerbandingan();
console.log('\n=== 3. BENCHMARK WAKTU ===');
benchmark(UKURAN_BENCHMARK_KECIL, true);
benchmark(UKURAN_BENCHMARK_BESAR, false);

if (jumlahGagal > 0) {
  console.error(`\n${jumlahGagal} uji GAGAL`);
  process.exitCode = 1;
}

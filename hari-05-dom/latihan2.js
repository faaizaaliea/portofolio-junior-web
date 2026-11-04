const peserta = [
  { id: 1, nama: "Sari", kelas: "Web", nilai: 90 },
  { id: 2, nama: "Budi", kelas: "Web", nilai: 68 },
  { id: 3, nama: "Citra", kelas: "Data", nilai: 82 },

  // 8 peserta tambahan
  { id: 4, nama: "Dina", kelas: "Web", nilai: 75 },
  { id: 5, nama: "Eko", kelas: "Data", nilai: 88 },
  { id: 6, nama: "Fani", kelas: "Data", nilai: 65 },
  { id: 7, nama: "Gilang", kelas: "Web", nilai: 78 },
  { id: 8, nama: "Hana", kelas: "Data", nilai: 95 },
  { id: 9, nama: "Indra", kelas: "Web", nilai: 60 },
  { id: 10, nama: "Joko", kelas: "Data", nilai: 72 },
  { id: 11, nama: "Kiki", kelas: "Web", nilai: 85 },
];

// 1. Daftar nama peserta yang lulus (nilai >= 70)
function daftarLulus() {
  return peserta.filter((p) => p.nilai >= 70).map((p) => p.nama);
}

// 2. Rata-rata nilai per kelas
function rataRataPerKelas() {
  const hasil = {};

  for (const p of peserta) {
    if (!hasil[p.kelas]) {
      hasil[p.kelas] = {
        total: 0,
        jumlah: 0,
      };
    }

    hasil[p.kelas].total += p.nilai;
    hasil[p.kelas].jumlah++;
  }

  for (const kelas in hasil) {
    hasil[kelas] = hasil[kelas].total / hasil[kelas].jumlah;
  }

  return hasil;
}

// 3. Peserta dengan nilai tertinggi
function pesertaTertinggi() {
  return peserta.reduce((tertinggi, p) => {
    if (p.nilai > tertinggi.nilai) {
      return p;
    }

    return tertinggi;
  });
}

// 4. Pencarian peserta berdasarkan sebagian nama
function cariPeserta(kataKunci) {
  return peserta.filter((p) =>
    p.nama.toLowerCase().includes(kataKunci.toLowerCase()),
  );
}

// 5. Daftar peringkat dengan nomor urut
function daftarPeringkat() {
  const urutan = [...peserta].sort((a, b) => b.nilai - a.nilai);

  return urutan.map((p, index) => ({
    peringkat: index + 1,
    nama: p.nama,
    nilai: p.nilai,
  }));
}

// 6. Menghapus duplikat email
function emailUnik() {
  const email = [
    "sari@email.com",
    "budi@email.com",
    "sari@email.com",
    "citra@email.com",
    "budi@email.com",
  ];

  return [...new Set(email)];
}

// Menampilkan hasil
console.log("1. Peserta lulus:", daftarLulus());
console.log("2. Rata-rata per kelas:", rataRataPerKelas());
console.log("3. Nilai tertinggi:", pesertaTertinggi());
console.log("4. Hasil pencarian:", cariPeserta("an"));
console.log("5. Daftar peringkat:", daftarPeringkat());
console.log("6. Email unik:", emailUnik());

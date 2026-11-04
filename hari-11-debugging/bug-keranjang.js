const keranjang = [
  { nama: "Buku", harga: 25000, jumlah: 2 },
  { nama: "Pensil", harga: 3000, jumlah: 10 },
  { nama: "Penghapus", harga: 2000, jumlah: 1 },
];
function hitungSubtotal(item) {
  return item.harga * item.jumlah;
}
function hitungTotal(daftar) {
  let total = 0;
  for (let i = 0; i < daftar.length; i++) {
    total += hitungSubtotal(daftar[i]);
  }
  return total;
}
function hitungDiskon(total) {
  if (total >= 50000) {
    return total * 0.1;
  }
  return 0;
}
function formatRupiah(angka) {
  return "Rp" + angka.toLocaleString("id-ID");
}
const total = hitungTotal(keranjang);
const diskon = hitungDiskon(total);
console.log("Total bayar: " + formatRupiah(total - diskon));
console.assert(hitungDiskon(50000) === 5000, "diskon batas 50000");

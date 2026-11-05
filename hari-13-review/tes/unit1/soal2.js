var keranjang = [
  { id: 1, barang: "T-shirt", harga: 80000, banyak: 2 },
  { id: 2, barang: "Jeans", harga: 90000, banyak: 1 },
];

function inputBarang(id, barang, harga, banyak) {
  keranjang.push({ id, barang, harga, banyak });
}

function totalHarga(keranjang) {
  return keranjang.reduce((total, item) => total + item.harga * item.banyak, 0);
}

console.log("Total harga:", totalHarga(keranjang));

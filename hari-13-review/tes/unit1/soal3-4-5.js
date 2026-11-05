var etalase = [
  { id: 1, barang: "T-shirt", harga: 80000 },
  { id: 2, barang: "Jeans", harga: 90000 },
  { id: 3, barang: "Tote Bag", harga: 20000 },
  { id: 4, barang: "School Bag", harga: 25000 },
  { id: 5, barang: "Shoes", harga: 120000 },
];
function urutkanTermurah(etalase) {
  return [...etalase].sort((a, b) => a.harga - b.harga);
}

console.log("Urutkan termurah:", urutkanTermurah(etalase));

// fungsi cari barang
function cariBarang(etalase, kataKunci) {
  return etalase.filter((item) =>
    item.barang.toLowerCase().includes(kataKunci.toLowerCase()),
  );
}

console.log("Cari Barang:", cariBarang(etalase, "bag"));

// fungsi cari barang termurah
function cariBarangTermurah(etalase) {
  let itemTermurah = etalase.reduce((termurah, item) =>
    item.harga < termurah.harga ? item : termurah,
  );
  return itemTermurah;
}

console.log("Cari Barang Termurah: ", cariBarangTermurah(etalase));

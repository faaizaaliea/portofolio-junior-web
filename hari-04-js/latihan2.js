// Ganjil/Genap dan Positif/Negatif/Nol
const daftarBilangan = [1, 2, 0, -1, -4, 7];

for (let i = 0; i < daftarBilangan.length; i++) {
  const bilangan = daftarBilangan[i];

  if (bilangan === 0) {
    console.log(`${bilangan} adalah nol`);
  } else {
    const jenisParitas = bilangan % 2 === 0 ? "genap" : "ganjil";
    const tanda = bilangan > 0 ? "positif" : "negatif";
    console.log(`${bilangan} adalah bilangan ${jenisParitas} ${tanda}`);
  }
}

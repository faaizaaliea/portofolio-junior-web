// Tarif parkir: 2 jam pertama Rp3.000, tiap jam berikutnya Rp2.000,
// maksimum Rp15.000 per hari. Sisa waktu kurang dari 1 jam dihitung 1 jam penuh.
const TARIF_DUA_JAM_PERTAMA = 3000;
const JAM_PERTAMA = 2;
const TARIF_PER_JAM_BERIKUTNYA = 2000;
const TARIF_MAKSIMUM = 15000;

// Durasi dalam jam (2.5 dan 0 sengaja disertakan untuk menguji kasus tepi)
const daftarDurasiParkir = [3, 7, 10, 15, 1, 2.5, 0];

for (let i = 0; i < daftarDurasiParkir.length; i++) {
  const durasi = daftarDurasiParkir[i];

  if (Number.isNaN(durasi) || durasi <= 0) {
    console.log(`Durasi parkir ${durasi} jam tidak valid`);
    continue;
  }

  // Pembulatan ke atas: 2.5 jam dihitung 3 jam
  const jamDihitung = Math.ceil(durasi);
  let biaya = TARIF_DUA_JAM_PERTAMA;

  if (jamDihitung > JAM_PERTAMA) {
    biaya += (jamDihitung - JAM_PERTAMA) * TARIF_PER_JAM_BERIKUTNYA;
  }

  const kenaMaksimum = biaya > TARIF_MAKSIMUM;
  if (kenaMaksimum) {
    biaya = TARIF_MAKSIMUM;
  }

  console.log(`Durasi parkir ${durasi} jam, biaya parkir: Rp ${biaya}`);
  if (kenaMaksimum) {
    console.log("(Biaya parkir mencapai batas maksimum Rp 15.000)");
  }
}

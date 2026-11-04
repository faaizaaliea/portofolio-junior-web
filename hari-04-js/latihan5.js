// Penentu kategori BMI
const BMI_KURANG = 18.5;
const BMI_NORMAL = 25;
const BMI_LEBIH = 30;

const berat = 56; // kg
const tinggi = 1.56; // meter

if (Number.isNaN(berat) || Number.isNaN(tinggi) || berat <= 0 || tinggi <= 0) {
  console.log("Berat atau tinggi tidak valid");
} else {
  const hasilBMI = berat / (tinggi * tinggi);
  console.log(`BMI Anda: ${hasilBMI.toFixed(2)}`);

  // Kategori hanya ditentukan bila data valid
  if (hasilBMI < BMI_KURANG) {
    console.log("Berat badan kurang");
  } else if (hasilBMI < BMI_NORMAL) {
    console.log("Berat badan normal");
  } else if (hasilBMI < BMI_LEBIH) {
    console.log("Berat badan lebih");
  } else {
    console.log("Obesitas");
  }
}

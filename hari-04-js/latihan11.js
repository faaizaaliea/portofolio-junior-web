const angkaRahasia = Math.floor(Math.random() * 100) + 1;
let tebakan;

do {
  tebakan = Number(prompt("Tebak angka dari 1 sampai 100:"));

  if (tebakan < angkaRahasia) {
    alert("Terlalu rendah! Coba angka yang lebih tinggi.");
  } else if (tebakan > angkaRahasia) {
    alert("Terlalu tinggi! Coba angka yang lebih rendah.");
  } else {
    alert("Benar! Kamu berhasil menebak angkanya.");
  }
} while (tebakan !== angkaRahasia);

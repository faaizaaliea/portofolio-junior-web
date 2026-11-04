let name = "Faaiza Muqorribati Lil'Aaliea";
let age = 20;
let height = 1.56;
let active = true;

console.log(
  `Nama: ${name}\nUmur: ${age} tahun\nTinggi: ${height} m\nAktif: ${active}`,
);

console.log(typeof "Halo");
console.log(typeof 25);
console.log(typeof ("5" + 3)); // "string"
console.log(typeof ("5" * 3)); // "number"
console.log(typeof (0.1 + 0.2)); // "number"
console.log(typeof Number("abc")); // "number"
console.log(typeof (10 / 3)); // "number"
console.log(typeof (10 % 3)); // "number"
console.log(typeof (2 ** 10)); // "number"

const jariJari = 7;
const luas = Math.PI * jariJari ** 2;
const keliling = 2 * Math.PI * jariJari;

console.log(`Luas: ${luas}`);
console.log(`Keliling: ${keliling}`);

const detikSehari = 24 * 60 * 60;
console.log(`Detik sehari: ${detikSehari}`);

const celsius = 30;
const fahrenheit = (celsius * 9) / 5 + 32;
console.log(`${celsius}°C = ${fahrenheit}°F`);

console.log(`5 + 3 = ${5 + 3}`);
console.log(`5 * 3 = ${5 * 3}`);
console.log(`0.1 + 0.2 = ${0.1 + 0.2}`);
console.log(`10 / 3 = ${10 / 3}`);
console.log(`10 % 3 = ${10 % 3}`);
console.log(`2 ** 10 = ${2 ** 10}`);

// Kenapa 0.1 + 0.2 bukan 0.3?
// JavaScript menyimpan number sebagai bilangan biner floating point (IEEE 754).
// Pecahan desimal seperti 0.1 dan 0.2 tidak bisa dinyatakan tepat dalam biner,
// sehingga tersimpan sebagai pendekatan. Jumlahnya menjadi 0.30000000000000004.
// Solusi: bandingkan dengan toleransi, atau bulatkan, mis. (0.1 + 0.2).toFixed(2).
console.log(`(0.1 + 0.2).toFixed(2) = ${(0.1 + 0.2).toFixed(2)}`);

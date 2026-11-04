// Kalkulator sederhana dengan switch
const daftarSoal = [
  { angka1: 20, operator: "/", angka2: 3 },
  { angka1: 20, operator: "/", angka2: 0 },
  { angka1: 7, operator: "+", angka2: 5 },
  { angka1: 7, operator: "-", angka2: 5 },
  { angka1: 7, operator: "*", angka2: 5 },
  { angka1: 7, operator: "%", angka2: 5 },
];

for (let i = 0; i < daftarSoal.length; i++) {
  const { angka1, operator, angka2 } = daftarSoal[i];
  let hasil;

  switch (operator) {
    case "+":
      hasil = angka1 + angka2;
      break;

    case "-":
      hasil = angka1 - angka2;
      break;

    case "*":
      hasil = angka1 * angka2;
      break;

    case "/":
      if (angka2 === 0) {
        console.log(`${angka1} / ${angka2}: tidak dapat membagi dengan nol`);
      } else {
        hasil = angka1 / angka2;
      }
      break;

    default:
      console.log(`${angka1} ${operator} ${angka2}: operator tidak valid`);
  }

  if (hasil !== undefined) {
    console.log(`${angka1} ${operator} ${angka2} = ${hasil}`);
  }
}

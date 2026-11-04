// Lab 4 (Node.js): kalkulator sederhana versi terminal
const readline = require("readline/promises");
const { stdin: input, stdout: output } = require("process");

async function main() {
  const rl = readline.createInterface({ input, output });
  let ulang = true;

  while (ulang) {
    const teksAngka1 = (await rl.question("Angka pertama: ")).trim();
    const operator = (await rl.question("Operator (+, -, *, /): ")).trim();
    const teksAngka2 = (await rl.question("Angka kedua: ")).trim();

    // Number("") bernilai 0, jadi string kosong dicek terpisah dari isNaN
    const angka1 = Number(teksAngka1);
    const angka2 = Number(teksAngka2);

    if (
      teksAngka1 === "" ||
      teksAngka2 === "" ||
      Number.isNaN(angka1) ||
      Number.isNaN(angka2)
    ) {
      console.log("Input tidak valid: angka harus berupa bilangan.");
    } else {
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
            console.log("Tidak dapat membagi dengan nol.");
          } else {
            hasil = angka1 / angka2;
          }
          break;
        default:
          console.log("Operator tidak valid. Gunakan +, -, *, atau /.");
      }

      if (hasil !== undefined) {
        console.log(`Hasil: ${angka1} ${operator} ${angka2} = ${hasil}`);
      }
    }

    const jawaban = (await rl.question("Hitung lagi? (y/n): "))
      .trim()
      .toLowerCase();
    ulang = jawaban === "y";
  }

  console.log("Terima kasih.");
  rl.close();
}

main();

// 1. Perulangan: pemisah di for harus titik koma (;)
function loopToTen() {
  for (var i = 0; i < 10; i++) {
    console.log(i);
  }
}

// 2. Bilangan genap: loop sampai akhir array, cek elemen (numbers[i]),
//    pakai === untuk membandingkan, dan return di luar loop
function displayEvenNumbers(numbers) {
  var evenNumbers = [];
  for (var i = 0; i < numbers.length; i++) {
    if (numbers[i] % 2 === 0) {
      evenNumbers.push(numbers[i]);
    }
  }
  return evenNumbers;
}
console.log(displayEvenNumbers([1, 2, 3, 4, 5, 6, 7, 8])); // [2, 4, 6, 8]

// 3. Tanda kutip: atribut HTML pakai " dan string JS pakai '
let html = '<p>Click here to <a href="#Book">reservation</a></p>';
console.log(html);

// 4. "3" adalah string sehingga 7 + "3" = "73"; ubah ke angka dulu
let seven = 7;
let three = "3";
let total = seven + Number(three);
console.log(total); // 10

// 5. String substitution: %s teks, %d bilangan bulat, %f bilangan desimal
console.log(
  "Hi %s, my name is %s, i'm %d years old, %f cm height and %f kg weight. I have %d children, %s, %s, and %s",
  "world", "Joe", 23, 169.9, 80.5, 3, "Sandra", "Bella", "Surdi",
);

loopToTen();

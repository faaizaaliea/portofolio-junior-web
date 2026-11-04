for (let i = 1; i <= 10; i++) {
  let baris = "";

  for (let j = 1; j <= 10; j++) {
    let hasil = i * j;
    baris += (hasil < 10 ? "   " : hasil < 100 ? "  " : " ") + hasil;
  }

  console.log(`Perkalian ${i} :` + baris);
}

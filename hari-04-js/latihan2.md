# Pseudocode Program Percabangan (Lab 2)

## 1. Penentu Bilangan (ganjil/genap dan positif/negatif/nol)

```
MULAI
  BACA bilangan

  JIKA bilangan = 0 MAKA
    TAMPILKAN "nol"
  JIKA TIDAK
    JIKA bilangan MOD 2 = 0 MAKA
      paritas = "genap"
    JIKA TIDAK
      paritas = "ganjil"
    AKHIR JIKA

    JIKA bilangan > 0 MAKA
      tanda = "positif"
    JIKA TIDAK
      tanda = "negatif"
    AKHIR JIKA

    TAMPILKAN "bilangan " + paritas + " " + tanda
  AKHIR JIKA
SELESAI
```

## 2. Tarif Parkir

```
MULAI
  KONSTANTA TARIF_DUA_JAM_PERTAMA = 3000
  KONSTANTA TARIF_PER_JAM_BERIKUTNYA = 2000
  KONSTANTA TARIF_MAKSIMUM = 15000

  BACA durasi

  JIKA durasi <= 0 MAKA
    TAMPILKAN "Durasi tidak valid"
  JIKA TIDAK
    jamDihitung = PEMBULATAN KE ATAS(durasi)
    biaya = TARIF_DUA_JAM_PERTAMA

    JIKA jamDihitung > 2 MAKA
      biaya = biaya + (jamDihitung - 2) * TARIF_PER_JAM_BERIKUTNYA
    AKHIR JIKA

    JIKA biaya > TARIF_MAKSIMUM MAKA
      biaya = TARIF_MAKSIMUM
    AKHIR JIKA

    TAMPILKAN biaya
  AKHIR JIKA
SELESAI
```

## 3. Kalkulator Sederhana

```
MULAI
  BACA angka1
  BACA operator
  BACA angka2

  PILIH operator
    KASUS "+" : hasil = angka1 + angka2
    KASUS "-" : hasil = angka1 - angka2
    KASUS "*" : hasil = angka1 * angka2
    KASUS "/" :
      JIKA angka2 = 0 MAKA
        TAMPILKAN "Tidak dapat membagi dengan nol"
      JIKA TIDAK
        hasil = angka1 / angka2
      AKHIR JIKA
    LAINNYA :
      TAMPILKAN "Operator tidak valid"
  AKHIR PILIH

  JIKA hasil sudah terisi MAKA
    TAMPILKAN hasil
  AKHIR JIKA
SELESAI
```

## 4. Kategori BMI

```
MULAI
  BACA berat
  BACA tinggi

  JIKA berat <= 0 ATAU tinggi <= 0 MAKA
    TAMPILKAN "Berat atau tinggi tidak valid"
  JIKA TIDAK
    bmi = berat / (tinggi * tinggi)

    JIKA bmi < 18.5 MAKA
      kategori = "Kurang"
    JIKA TIDAK, JIKA bmi < 25 MAKA
      kategori = "Normal"
    JIKA TIDAK, JIKA bmi < 30 MAKA
      kategori = "Lebih"
    JIKA TIDAK
      kategori = "Obesitas"
    AKHIR JIKA

    TAMPILKAN bmi, kategori
  AKHIR JIKA
SELESAI
```

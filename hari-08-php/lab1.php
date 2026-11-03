<?php
declare(strict_types=1);
$nama = 'Sari';
$umur = 21;
$status = "Mahasiswa";
echo 'Halo, saya ' . $nama . ' umur saya ' . $umur . ' tahun dan status saya '. $status . '!';
echo '</br>';
var_dump($nama, $umur, $status);
echo '<table border="1">';
  for ($i = 1; $i <= 10; $i++) {
    echo '<tr>';
    for ($j = 1; $j <= 10; $j++) {
      echo '<td>' . $i * $j . '</td>';
    }
    echo '</tr>';
  }
echo '</table>';?>

<?php
// bilangan ganjil genap 1-20
echo 'Ganjil: ' ;
for ($i = 1; $i <= 20; $i++) {
  if ($i % 2 != 0) {
    echo $i . ' ';
  }
}
echo '<br>';
echo 'Genap: ' ;
for ($i = 1; $i <= 20; $i++) {
  if ($i % 2 == 0) {
    echo $i . ' ';
  }
} ?>

<?php
// FizzBuzz 1-30
echo '<h1><u>FizzBuzz</h1>';  
for ($i = 1; $i <= 30; $i++) {
  if ($i % 3 == 0 && $i % 5 == 0) {
    echo 'FizzBuzz ';
  } elseif ($i % 3 == 0) {
    echo 'Fizz ';
  } elseif ($i % 5 == 0) {
    echo 'Buzz ';
  } else {
    echo $i . ' ';
  }
} ?>

<?php 
echo '<h2>Konversi nilai ke Grade</h2>';
$nilai = 80;
$grade = match (true) {
  $nilai >= 80 => 'A',
  $nilai >= 70 => 'B',
  $nilai >= 60 => 'C',
  default => 'D',
};

echo 'Nilai: ' . $nilai . ' Grade: ' . $grade; '<br>'?>

<?php
$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu',];
$hariIni = date('w');
echo '<h2>Kalender</h2>';
echo 'Hari ini adalah hari ' . $hariIni;
echo '<br>';
echo 'Hari ini adalah hari (aturan fungsi data php) ' . $hari[$hariIni]; 
echo 'Waktu server'. date('d-m-Y H:i:s') . '<br>';?> 

<p>Waktu server: <?= date('d-m-Y H:i') ?></p>
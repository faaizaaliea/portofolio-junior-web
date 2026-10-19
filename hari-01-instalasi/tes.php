<?php
echo 'Instalasi Berhasil! Lingkungan pemrograman siap digunakan.<br>';
echo 'Versi PHP: ' . PHP_VERSION . '<br>';
echo 'Server: ' . $_SERVER['SERVER_SOFTWARE'] . '<br>';
echo 'Metode request: ' . $_SERVER['REQUEST_METHOD'] . '<br>';
echo 'Waktu server: ' . date('d-m-Y H:i:s');

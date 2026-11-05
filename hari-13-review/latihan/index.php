<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $no_hp = $_POST['no_hp'] ?? '';
    $kursus = $_POST['kursus'] ?? '';
    $galat = [];
    if ($nama === '') {
        $galat['nama'] = 'Nama harus diisi.';
    }
    if ($email === '') {
        $galat['email'] = 'Email harus diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $galat['email'] = 'Email tidak valid.';
    }
    if ($kursus === '') {
        $galat['kursus'] = 'Kursus harus dipilih.';
    }
}
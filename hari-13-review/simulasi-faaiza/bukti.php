<?php
declare(strict_types=1);
/* Ekspor bukti pendaftaran ke PDF memakai FPDF 1.9.0. */
require_once __DIR__ . '/fungsi.php';
require_once __DIR__ . '/vendor/fpdf/fpdf.php';

/**
 * Mengubah teks UTF-8 ke Windows-1252 agar terbaca oleh FPDF.
 *
 * @param string $teks Teks UTF-8.
 * @return string Teks Windows-1252.
 */
function teksPdf(string $teks): string
{
    return mb_convert_encoding($teks, 'Windows-1252', 'UTF-8');
}

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$peserta = null;
if ($id !== false && $id !== null) {
    try {
        $peserta = ambilPeserta(koneksi(), $id);
    } catch (Throwable $ex) {
        error_log($ex->getMessage());
        http_response_code(500);
        exit('Terjadi kesalahan pada sistem.');
    }
}
if ($peserta === null) {
    http_response_code(404);
    exit('Data tidak ditemukan.');
}

$nomor = nomorBukti((int) $peserta['id'], (string) $peserta['tgl_daftar']);

$pdf = new FPDF('P', 'mm', 'A5');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Bukti Pendaftaran Kursus', 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Nomor: ' . $nomor, 0, 1);
$pdf->Ln(4);

$baris = [
    'Nama' => $peserta['nama'],
    'Email' => $peserta['email'],
    'No. HP' => $peserta['no_hp'],
    'Kursus' => $peserta['nama_kursus'],
    'Tanggal daftar' => $peserta['tgl_daftar'],
];
foreach ($baris as $label => $nilai) {
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 8, $label, 'B', 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 8, teksPdf((string) $nilai), 'B');
}

$pdf->Output('I', $nomor . '.pdf');

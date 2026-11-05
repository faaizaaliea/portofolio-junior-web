<?php
declare(strict_types=1);
/** cetak-fpdf.php  alternatif bukti PDF dengan FPDF (perbandingan library, lihat docs/daftar-library.md). */
require_once __DIR__ . '/../src/bootstrap.php';
wajibLogin();
require_once __DIR__ . '/../vendor/autoload.php';

$id = ambilIdPositif($_GET);
$bukti = ($id === null) ? null : ambilBuktiPendaftar(getConnection(), $id);
if ($bukti === null) {
    http_response_code(404);
    exit('Data tidak ditemukan.');
}

$nomor = formatNomorBukti((int) $bukti['id'], (int) date('Y', strtotime($bukti['tgl_daftar'])));

function teksPdf(string $teks): string
{
    return mb_convert_encoding($teks, 'Windows-1252', 'UTF-8');
}

$pdf = new FPDF('P', 'mm', 'A5');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Bukti Pendaftaran', 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Nomor: ' . $nomor, 0, 1);
$pdf->Ln(4);

$baris = [
    'Nama' => $bukti['nama'],
    'Email' => $bukti['email'],
    'No. HP' => $bukti['no_hp'],
    'Kelas' => $bukti['nama_kelas'],
    'Tanggal daftar' => $bukti['tgl_daftar'],
];
foreach ($baris as $label => $nilai) {
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 8, $label, 'B', 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 8, teksPdf((string) $nilai), 'B');
}

$pdf->Ln(4);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 5, 'Dicetak pada ' . date('d-m-Y H:i'), 0, 1);
$pdf->Output('I', $nomor . '.pdf');
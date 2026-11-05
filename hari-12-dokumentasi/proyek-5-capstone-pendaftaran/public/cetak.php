<?php
declare(strict_types=1);
/** cetak.php  bukti pendaftaran PDF (Dompdf). Wajib login admin. */
require_once __DIR__ . '/../src/bootstrap.php';
wajibLogin();
require_once __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
$id = ambilIdPositif($_GET);
$bukti = ($id === null) ? null : ambilBuktiPendaftar(getConnection(), $id);
if ($bukti === null) {
http_response_code(404);
exit('Data tidak ditemukan.');
}
$nomor = formatNomorBukti((int) $bukti['id'], (int) date('Y', strtotime($bukti['tgl_daftar'])));
ob_start();
require __DIR__ . '/../templates/bukti.php';
$html = (string) ob_get_clean();
$options = new Options();
$options->set('isRemoteEnabled', false);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A5', 'portrait');
$dompdf->render();
$dompdf->stream($nomor . '.pdf', ['Attachment' => false]);
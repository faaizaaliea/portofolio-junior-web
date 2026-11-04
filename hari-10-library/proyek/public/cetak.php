<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';
cekLoginAdmin(); // bukti hanya untuk admin yang login
require_once __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range'
=> 1]]);
$bukti = ($id === false || $id === null) ? null :
ambilBuktiPendaftar(getConnection(), $id);
if ($bukti === null) {
http_response_code(404);
exit('Data tidak ditemukan.');
}
$nomor = sprintf('REG-%s-%06d', date('Y', strtotime($bukti['tgl_daftar'])),
$bukti['id']);
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
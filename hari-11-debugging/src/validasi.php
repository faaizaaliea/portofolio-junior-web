<?php
declare(strict_types=1);
function validNoHp(string $noHp): bool
{
return preg_match('/^08[0-9]{8,11}$/', $noHp) === 1;
}
function hitungSisaKuota(int $kuota, int $terisi): int
{
    return max(0, $kuota - $terisi);
}
function formatNomorBukti(int $tahun, int $id): string
{
return sprintf('REG-%d-%06d', $tahun, $id);
}
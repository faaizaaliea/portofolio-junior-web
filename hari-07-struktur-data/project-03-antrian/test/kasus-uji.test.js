import assert from 'node:assert/strict';
import { test } from 'node:test';
import { Antrian } from '../js/antrian.js';
import { RiwayatTeks } from '../js/riwayat.js';
import { ErrorStrukturKosong, LinkedQueue, Stack } from '../js/structures.js';

test('ambil 3 nomor lalu panggil 4 kali (ke-4 antrean kosong)', () => {
  const antrian = new Antrian();
  ['A001', 'A002', 'A003'].forEach((nomor) => {
    assert.equal(antrian.ambilNomor(), nomor);
  });
  assert.equal(antrian.panggilBerikutnya(), 'A001');
  assert.equal(antrian.panggilBerikutnya(), 'A002');
  assert.equal(antrian.panggilBerikutnya(), 'A003');
  assert.throws(() => antrian.panggilBerikutnya(), ErrorStrukturKosong);
  assert.equal(antrian.jumlahMenunggu, 0);
});

test('riwayat panggilan dibatasi 5 nomor, terbaru dulu', () => {
  const antrian = new Antrian();
  for (let i = 0; i < 7; i += 1) antrian.ambilNomor();
  for (let i = 0; i < 7; i += 1) antrian.panggilBerikutnya();
  assert.deepEqual(antrian.riwayatPanggilan(), [
    'A007',
    'A006',
    'A005',
    'A004',
    'A003',
  ]);
});

test('undo 3 kali lalu redo 2 kali', () => {
  const riwayat = new RiwayatTeks();
  ['a', 'ab', 'abc'].forEach((teks) => riwayat.ubah(teks));
  riwayat.undo();
  riwayat.undo();
  assert.equal(riwayat.undo(), '');
  assert.equal(riwayat.bisaUndo, false);
  riwayat.redo();
  assert.equal(riwayat.redo(), 'ab');
  assert.equal(riwayat.bisaRedo, true);
});

test('mengetik setelah undo menghapus redo', () => {
  const riwayat = new RiwayatTeks();
  ['a', 'ab'].forEach((teks) => riwayat.ubah(teks));
  riwayat.undo();
  assert.equal(riwayat.bisaRedo, true);
  riwayat.ubah('ax');
  assert.equal(riwayat.bisaRedo, false);
});

test('riwayat dibatasi 50 langkah', () => {
  const riwayat = new RiwayatTeks();
  for (let i = 1; i <= 60; i += 1) riwayat.ubah('x'.repeat(i));
  assert.equal(riwayat.jumlahLangkah, 50);
});

test('reset saat antrean berisi mengembalikan ke kondisi awal', () => {
  const antrian = new Antrian();
  antrian.ambilNomor();
  antrian.ambilNomor();
  antrian.panggilBerikutnya();
  antrian.reset();
  assert.equal(antrian.jumlahMenunggu, 0);
  assert.equal(antrian.nomorSekarang, null);
  assert.deepEqual(antrian.riwayatPanggilan(), []);
  assert.equal(antrian.ambilNomor(), 'A001');
});

test('penanganan galat struktur data dan input tidak valid', () => {
  assert.throws(() => new Stack().pop(), ErrorStrukturKosong);
  assert.throws(() => new LinkedQueue().dequeue(), ErrorStrukturKosong);
  assert.throws(() => new Stack(0), RangeError);
  assert.throws(() => new RiwayatTeks().ubah(123), TypeError);
  assert.throws(() => new RiwayatTeks().undo(), ErrorStrukturKosong);
});

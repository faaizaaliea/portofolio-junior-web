#!/usr/bin/env bash
# Uji asap (smoke test) lewat HTTP  memetakan sebagian kasus uji di docs/kasus-uji.md.
# Cara pakai (server harus sudah jalan, admin sudah dibuat):
#   BASE=http://localhost/pendaftaran/public ADMIN_USER=admin ADMIN_PASS=SandiKuat123 bash tests/uji-http.sh
# Perlu: bash, curl. Aman diulang: data uji memakai akhiran waktu unik dan kelas uji dibuat sendiri.
# Catatan: skrip MEMBUAT data uji (kelas "UJI-*" dan pendaftar "uji*@contoh.test")  jangan dijalankan di data produksi.
BASE="${BASE:-http://127.0.0.1:8080}"; U="${ADMIN_USER:-admin}"; P="${ADMIN_PASS:-rahasia123}"
SFX=$(date +%s); D=$(mktemp -d); LOLOS=0; GAGAL=0

ok()   { LOLOS=$((LOLOS+1)); echo "[LOLOS] $1"; }
bad()  { GAGAL=$((GAGAL+1)); echo "[GAGAL] $1  $2"; }
cek()  { if [ "$2" = "$3" ]; then ok "$1"; else bad "$1" "diharapkan '$2', aktual '$3'"; fi; }
ada()  { if grep -q -- "$2" <<<"$3"; then ok "$1"; else bad "$1" "teks '$2' tidak ditemukan"; fi; }
token(){ curl -s -b "$1" -c "$1" "$BASE/$2" | grep -o "name=['\"]csrf['\"] value=['\"][a-f0-9]*" | head -1 | sed "s/.*value=.//"; }
kode() { curl -s -o /dev/null -w "%{http_code}" "$@"; }
post() { # post <jar> <halaman-sumber-token> <halaman-tujuan> <data...>
  local jar="$1" src="$2" dst="$3"; shift 3; local t; t=$(token "$jar" "$src")
  curl -s -b "$jar" -c "$jar" -w "\n__KODE=%{http_code} __ALIH=%{redirect_url}" --data-urlencode "csrf=$t" "$@" "$BASE/$dst"
}
daftar() { post "$1" daftar.php daftar.php -d "nama=$2" -d "email=$3" -d "no_hp=$4" -d "alamat=Jl. Uji" -d "kelas_id=$5"; }
kodeDari() { sed -n 's/.*__KODE=\([0-9]*\).*/\1/p' <<<"$1"; }

echo "== Persiapan: login admin dan buat kelas uji =="
A="$D/admin"; R=$(post "$A" login.php login.php -d "username=$U" -d "sandi=$P")
cek "TC-13 Login benar → 302" 302 "$(kodeDari "$R")"
post "$A" kelas-form.php kelas-form.php -d "nama_kelas=UJI-A-$SFX" -d "kuota=2" >/dev/null
post "$A" kelas-form.php kelas-form.php -d "nama_kelas=UJI-B-$SFX" -d "kuota=1" >/dev/null
KEL=$(curl -s -b "$A" "$BASE/kelas.php")
KA=$(python3 -c "
import re,sys
html=sys.argv[1]
for nm in ['A']:
    m=re.search(r'UJI-A-$SFX.*?kelas-form\.php\?id=(\d+)', html, re.S); print(m.group(1) if m else '')
" "$KEL")
KB=$(python3 -c "
import re,sys
m=re.search(r'UJI-B-$SFX.*?kelas-form\.php\?id=(\d+)', sys.argv[1], re.S); print(m.group(1) if m else '')
" "$KEL")
[ -n "$KA" ] && [ -n "$KB" ] && ok "Kelas uji dibuat (A=$KA kuota 2, B=$KB kuota 1)" || { bad "Kelas uji dibuat" "id tidak ditemukan"; exit 1; }

echo "== Pendaftaran =="
V="$D/v"
R=$(daftar "$V" "Budi Santoso" "uji1-$SFX@contoh.test" 0829982838484 "$KA"); cek "TC-01 Data valid → 302 sukses" 302 "$(kodeDari "$R")"
ada "TC-01 redirect ke status=sukses" "status=sukses" "$R"
R=$(daftar "$V" "" "uji2-$SFX@contoh.test" 0812345678 "$KA"); ada "TC-02 Nama kosong ditolak" "Nama 3" "$R"
R=$(daftar "$V" "Budi" "" 0812345678 "$KA"); ada "TC-03 Email kosong ditolak" "Format email tidak valid" "$R"
R=$(daftar "$V" "Budi" "uji3-$SFX@contoh.test" "" "$KA"); ada "TC-04 HP kosong ditolak" "Nomor HP diawali 08" "$R"
R=$(daftar "$V" "Budi" "uji4-$SFX@contoh.test" 0812345678 ""); ada "TC-05 Kelas kosong ditolak" "Pilih kelas yang tersedia" "$R"
R=$(daftar "$V" "Budi Dua" "uji1-$SFX@contoh.test" 0812345678 "$KB"); ada "TC-06 Email ganda ditolak" "Email sudah terdaftar" "$R"
R=$(daftar "$V" "Hp Sembilan" "uji5-$SFX@contoh.test" 081234567 "$KB"); ada "TC-07 HP 9 digit ditolak" "Nomor HP diawali 08" "$R"
R=$(daftar "$V" "Hp Empat Belas" "uji6-$SFX@contoh.test" 08123456789012 "$KB"); ada "TC-10 HP 14 digit ditolak" "Nomor HP diawali 08" "$R"
R=$(daftar "$V" "Hp Sepuluh" "uji7-$SFX@contoh.test" 0812345678 "$KA"); cek "TC-08/12 HP 10 digit diterima; kelas A (kuota 2) terisi 2" 302 "$(kodeDari "$R")"
R=$(daftar "$V" "Eks Satu" "uji8-$SFX@contoh.test" 0812345678901 "$KB"); cek "TC-09 HP 13 digit diterima (kelas B kuota 1 terisi 1)" 302 "$(kodeDari "$R")"
R=$(daftar "$V" "Terlambat" "uji9-$SFX@contoh.test" 0812345678 "$KB"); ada "TC-11 Kuota penuh ditolak" "Kuota kelas sudah penuh" "$R"
R=$(daftar "$V" "Terlambat Lagi" "uji10-$SFX@contoh.test" 0812345678 "$KA"); ada "TC-11b Kelas A (kuota 2) penuh pada pendaftar ke-3" "Kuota kelas sudah penuh" "$R"
R=$(daftar "$V" "x' OR '1'='1" "uji11-$SFX@contoh.test" 0812345678 "$KA"); ada "TC-21 Payload SQLi di nama tidak menimbulkan galat SQL" "Kuota kelas sudah penuh" "$R"

echo "== Akses & keamanan =="
cek "TC-16 index.php tanpa login → 302" 302 "$(kode "$BASE/index.php")"
for h in "tambah.php" "ubah.php?id=1" "cetak.php?id=1" "cetak-fpdf.php?id=1" "kelas.php" "kelas-form.php"; do cek "TC-17 $h tanpa login → 302" 302 "$(kode "$BASE/$h")"; done
cek "TC-17 POST hapus.php tanpa login → 302" 302 "$(kode -X POST -d id=1 "$BASE/hapus.php")"
cek "TC-18 daftar.php publik → 200" 200 "$(kode "$BASE/daftar.php")"
cek "TC-19 ubah.php?id=abc → 404" 404 "$(kode -b "$A" "$BASE/ubah.php?id=abc")"
cek "TC-22 hapus tanpa token → 403" 403 "$(kode -b "$A" -X POST -d id=1 "$BASE/hapus.php")"
cek "TC-22b hapus token salah → 403" 403 "$(kode -b "$A" -X POST -d id=1 -d csrf=salah "$BASE/hapus.php")"
cek "TC-22c kelas-hapus tanpa token → 403" 403 "$(kode -b "$A" -X POST -d id=1 "$BASE/kelas-hapus.php")"
cek "TC-23 logout lewat GET → 405" 405 "$(kode -b "$A" "$BASE/logout.php")"
cek "TC-23b hapus lewat GET → 405" 405 "$(kode -b "$A" "$BASE/hapus.php?id=1")"
cek "TC-23c sesi tetap aktif setelah GET logout → 200" 200 "$(kode -b "$A" "$BASE/index.php")"

echo "== XSS =="
post "$A" kelas-form.php kelas-form.php -d "nama_kelas=UJI-X-$SFX" -d "kuota=5" >/dev/null
KX=$(curl -s -b "$A" "$BASE/kelas.php" | python3 -c "import re,sys;m=re.search(r'UJI-X-$SFX.*?kelas-form\\.php\\?id=(\\d+)',sys.stdin.read(),re.S);print(m.group(1))")
post "$A" tambah.php tambah.php --data-urlencode "nama=<script>alert(1)</script>" -d "email=xss-$SFX@contoh.test" -d "no_hp=0812345678" -d "kelas_id=$KX" >/dev/null
IDX=$(curl -s -b "$A" "$BASE/index.php")
if grep -q "<script>alert(1)" <<<"$IDX"; then bad "TC-20 XSS tidak ter-escape" "ditemukan <script> mentah"; else ada "TC-20 XSS pada nama tampil ter-escape di index" "&lt;script&gt;alert(1)" "$IDX"; fi

echo "== Kuota saat ubah & kelas =="
KEL=$(curl -s -b "$A" "$BASE/kelas.php")
IDP=$(curl -s -b "$A" "$BASE/index.php" | grep -o "ubah.php?id=[0-9]*" | head -1 | grep -o "[0-9]*$")
R=$(post "$A" "ubah.php?id=$IDP" "ubah.php?id=$IDP" -d "nama=Pindah Kelas" -d "email=pindah-$SFX@contoh.test" -d "no_hp=0812345678" -d "kelas_id=$KB")
ada "TC-27 Ubah pindah ke kelas penuh ditolak" "Kuota kelas tujuan sudah penuh" "$R"
R=$(post "$A" kelas-form.php kelas-form.php -d "nama_kelas=ab" -d "kuota=0"); ada "TC-28 Kelas: nama pendek & kuota 0 ditolak" "Kuota 1" "$R"
R=$(post "$A" kelas-form.php kelas-form.php -d "nama_kelas=UJI-A-$SFX" -d "kuota=5"); ada "TC-29 Kelas: nama ganda ditolak" "Nama kelas sudah dipakai" "$R"
R=$(post "$A" "kelas-form.php?id=$KA" "kelas-form.php?id=$KA" -d "nama_kelas=UJI-A-$SFX" -d "kuota=1"); ada "TC-30 Kelas: kuota di bawah jumlah terisi ditolak" "tidak boleh di bawah" "$R"
R=$(post "$A" kelas.php kelas-hapus.php -d "id=$KA"); KEL=$(curl -s -b "$A" "$BASE/kelas.php"); ada "TC-31 Hapus kelas berisi pendaftar ditolak (kelas masih ada)" "UJI-A-$SFX" "$KEL"
ada "TC-32 Filter kelas tersedia di dashboard" "filterKelas" "$(curl -s -b "$A" "$BASE/index.php")"

echo "== Race condition (8 permintaan paralel, kelas kuota 1) =="
post "$A" kelas-form.php kelas-form.php -d "nama_kelas=UJI-R-$SFX" -d "kuota=1" >/dev/null
KR=$(curl -s -b "$A" "$BASE/kelas.php" | python3 -c "import re,sys;m=re.search(r'UJI-R-$SFX.*?kelas-form\.php\?id=(\d+)',sys.stdin.read(),re.S);print(m.group(1))")
for i in 1 2 3 4 5 6 7 8; do ( J="$D/r$i"; daftar "$J" "Racer $i" "race$i-$SFX@contoh.test" 0812345678 "$KR" | grep -o '__KODE=[0-9]*' | cut -d= -f2 > "$D/hasil$i" ) & done; wait
SUKSES=$(cat "$D"/hasil* | grep -c '^302$')
cek "TC-25 Hanya 1 dari 8 permintaan paralel berhasil" 1 "$SUKSES"

echo "== Login: gagal berulang & logout =="
L="$D/l"; for i in 1 2 3 4 5; do post "$L" login.php login.php -d "username=$U" -d "sandi=salah$i" >/dev/null; done
R=$(post "$L" login.php login.php -d "username=$U" -d "sandi=$P"); ada "TC-14/15 Setelah 5 gagal, login benar pun ditolak (terkunci)" "Terlalu banyak percobaan" "$R"
L2="$D/l2"; R=$(post "$L2" login.php login.php -d "username=$U" -d "sandi=salah"); ada "TC-14 Login salah: pesan umum" "Username atau kata sandi salah" "$R"
post "$A" index.php logout.php >/dev/null
cek "TC-24 Setelah logout, index.php → 302" 302 "$(kode -b "$A" "$BASE/index.php")"

echo; echo "Ringkasan: $LOLOS lolos, $GAGAL gagal"; rm -rf "$D"; [ "$GAGAL" -eq 0 ]

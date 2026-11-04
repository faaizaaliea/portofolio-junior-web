# Portofolio Hari 7 - Struktur Data II, Algoritma, dan Clean Code

| Folder                | Isi                                                   | Bukti                                                           |
| --------------------- | ----------------------------------------------------- | --------------------------------------------------------------- |
| `lab1/`               | Linked List, LinkedQueue, `remove()`, benchmark Queue | `HASIL.md` (uji, gambar pointer, tabel waktu)                   |
| `lab2/`               | Tree, BST, Graph, BFS/DFS, `jarakTerpendek`           | `node app.js`                                                   |
| `lab3/`               | Pencarian dan pengurutan, perbandingan, benchmark     | `HASIL.md` (tabel perbandingan, waktu, penjelasan Big-O)        |
| `lab4/`               | Code review dan refactor                              | `kode-buruk.js`, `tabel-temuan.md`, `versi-bersih.js`           |
| `lab5/`               | ESLint dan Prettier                                   | `laporan-eslint.md` (sebelum 9 -> sesudah 0)                    |
| `project-03-antrian/` | PBL Proyek 3: Simulator Antrean dan Riwayat           | `README.md`, `CODE-REVIEW.md`, `docs/` (screenshot), `npm test` |
| `screenshot/`         | Tangkapan layar terminal saat mengerjakan lab         | -                                                               |

## Menjalankan

```bash
node lab1/app.js
node lab2/app.js
node lab3/pencarian-pengurutan.js

cd lab5 && npm install && npx eslint kode-buruk.js kode-bersih.js
cd ../project-03-antrian && npm install && npm run lint && npm test
```

## Commit dan push

`node_modules/` tidak disertakan (ada di `.gitignore`); jalankan `npm install` di `lab5/` dan `project-03-antrian/`.

```bash
git add .
git commit -m 'Hari 7: Proyek 3 simulator antrean dan riwayat'
git push
```

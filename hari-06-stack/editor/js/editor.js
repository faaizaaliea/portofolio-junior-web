import { Stack } from "../../structures.js";

const BATAS_RIWAYAT = 50; // langkah maksimum yang diingat
const JEDA_MS = 500; // debounce: snapshot disimpan setelah berhenti mengetik

const editor = document.getElementById("editor");
const tombolUndo = document.getElementById("undo");
const tombolRedo = document.getElementById("redo");
const info = document.getElementById("info");

const undoStack = new Stack(BATAS_RIWAYAT);
const redoStack = new Stack(BATAS_RIWAYAT);
let teksSekarang = ""; // snapshot terakhir yang tersimpan
let timer = null;

/** Memperbarui status tombol dan teks info. */
function perbaruiTombol() {
  const adaPerubahanBelumDisimpan = editor.value !== teksSekarang;
  tombolUndo.disabled = undoStack.isEmpty() && !adaPerubahanBelumDisimpan;
  tombolRedo.disabled = redoStack.isEmpty();
  info.textContent = `Riwayat: ${undoStack.size()} undo, ${redoStack.size()} redo (maks ${BATAS_RIWAYAT})`;
}

/** Menyimpan snapshot bila teks berubah sejak snapshot terakhir. */
function simpanSnapshot() {
  clearTimeout(timer);
  timer = null;
  if (editor.value === teksSekarang) {
    return;
  }
  undoStack.push(teksSekarang);
  teksSekarang = editor.value;
  redoStack.clear(); // pengetikan baru membatalkan jalur redo
}

editor.addEventListener("input", () => {
  clearTimeout(timer);
  timer = setTimeout(() => {
    simpanSnapshot();
    perbaruiTombol();
  }, JEDA_MS);
  perbaruiTombol();
});

tombolUndo.addEventListener("click", () => {
  simpanSnapshot(); // jangan hilangkan ketikan yang belum sempat disimpan
  if (undoStack.isEmpty()) {
    return;
  }
  redoStack.push(teksSekarang);
  teksSekarang = undoStack.pop();
  editor.value = teksSekarang;
  perbaruiTombol();
});

tombolRedo.addEventListener("click", () => {
  if (redoStack.isEmpty()) {
    return;
  }
  undoStack.push(teksSekarang);
  teksSekarang = redoStack.pop();
  editor.value = teksSekarang;
  perbaruiTombol();
});

perbaruiTombol();

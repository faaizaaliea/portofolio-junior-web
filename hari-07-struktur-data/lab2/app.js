import {
  cetakPohon,
  hitungNode,
  cariNode,
  BinarySearchTree,
  bfs,
  dfs,
  jarakTerpendek,
  cetakBST,
} from "./structures.js";
// ========================================
// 1. TREE STRUKTUR FOLDER
// ========================================

console.log("=== 1. TREE STRUKTUR FOLDER ===");

const proyek = {
  nama: "proyek",
  children: [
    {
      nama: "js",
      children: [
        { nama: "app.js", children: [] },
        { nama: "structures.js", children: [] },
      ],
    },
    {
      nama: "css",
      children: [{ nama: "style.css", children: [] }],
    },
    {
      nama: "index.html",
      children: [],
    },
  ],
};

console.log("\nStruktur folder:");
cetakPohon(proyek);

console.log("\nJumlah node:", hitungNode(proyek));

console.log("Cari app.js:", cariNode(proyek, "app.js"));

console.log("Cari style.css:", cariNode(proyek, "style.css"));

console.log("Cari file tidak ada:", cariNode(proyek, "tidak-ada.js"));

// ========================================
// 2. BINARY SEARCH TREE (BST)
// ========================================

console.log("\n=== 2. BINARY SEARCH TREE ===");

const bst = new BinarySearchTree();

const angka = [50, 30, 70, 20, 40, 60, 80];

for (const nilai of angka) {
  bst.insert(nilai);
}

console.log("Data yang dimasukkan:", angka);
console.log("In-order:", bst.inOrder());

console.log("Apakah ada 60?", bst.has(60));
console.log("Apakah ada 65?", bst.has(65));

// ========================================
// 3. GRAPH JARINGAN 6 KOTA
// ========================================

console.log("\n=== 3. GRAPH JARINGAN KOTA ===");

// Adjacency list: setiap kota menyimpan
// daftar kota yang terhubung langsung dengannya.
const graf = {
  Solo: ["Yogyakarta", "Semarang"],
  Yogyakarta: ["Solo", "Magelang"],
  Semarang: ["Solo", "Salatiga", "Surabaya"],
  Magelang: ["Yogyakarta", "Salatiga"],
  Salatiga: ["Semarang", "Magelang", "Surabaya"],
  Surabaya: ["Semarang", "Salatiga"],
};

console.log("\nAdjacency list:");
console.log(graf);

console.log("\nBFS dari Solo:");
console.log(bfs(graf, "Solo"));

console.log("\nDFS dari Solo:");
console.log(dfs(graf, "Solo"));

console.log(
  "\nJarak terpendek Solo ke Surabaya:",
  jarakTerpendek(graf, "Solo", "Surabaya"),
  "sisi",
);

console.log(
  "Jarak terpendek Solo ke Magelang:",
  jarakTerpendek(graf, "Solo", "Magelang"),
  "sisi",
);

console.log("\nGambar Binary Search Tree:");
cetakBST(bst.root);

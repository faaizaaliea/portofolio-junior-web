<?php
/* templates/footer.php — $pakaiDataTables: muat jQuery dan DataTables (SweetAlert2 dan app.js selalu dimuat) */
?>
</main>
<footer class='container py-3'><small>&copy; <?= date('Y') ?> Pendaftaran Pelatihan</small></footer>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js'></script>
<?php if (!empty($pakaiDataTables)): ?>
<script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>
<script src='https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js'></script>
<script src='https://cdn.datatables.net/1.13.11/js/dataTables.bootstrap5.min.js'></script>
<?php endif; ?>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25/dist/sweetalert2.all.min.js'></script>
<script src='js/app.js'></script>
</body>
</html>
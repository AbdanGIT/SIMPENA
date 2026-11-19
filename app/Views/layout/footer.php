</section> <!-- Tutup section.content -->
</div> <!-- Tutup content-wrapper -->
</div> <!-- Tutup wrapper -->

<!-- FOOTER -->
<!-- PERBAIKAN: Hapus inline style margin-left, biarkan CSS yang mengatur -->
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0 (SIMSS)
  </div>
  <strong>Copyright &copy; <?= date('Y') ?> <a href="#">Nadifa Nabila</a>.</strong> All rights reserved.
</footer>

<!-- JS Utama -->
<script src="<?= base_url('assets/plugins/jQuery/jquery-2.2.3.min.js') ?>"></script>
<script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
<script>
  (function () {
      var body      = document.body;
      var tombol    = document.getElementById('mobileNavToggle');
      var backdrop  = document.getElementById('mobileNavBackdrop');
      var sidebar   = document.getElementById('sidebarKustom');
      var mediaHp   = window.matchMedia('(max-width: 991.98px)');

      function modeHp() {
          return mediaHp.matches;
      }

      function tutupLaci() {
          body.classList.remove('sidebar-mobile-open');
          if (tombol) {
              tombol.setAttribute('aria-expanded', 'false');
          }
      }

      // Dipakai tombol di sidebar maupun tombol mengambang.
      window.toggleSidebar = function () {
          if (modeHp()) {
              var terbuka = body.classList.toggle('sidebar-mobile-open');
              if (tombol) {
                  tombol.setAttribute('aria-expanded', terbuka ? 'true' : 'false');
              }

              return;
          }

          body.classList.toggle('sidebar-minimize');
      };

      if (tombol) {
          tombol.addEventListener('click', window.toggleSidebar);
      }

      if (backdrop) {
          backdrop.addEventListener('click', tutupLaci);
      }

      // Menu diklik -> laci otomatis tertutup di HP.
      if (sidebar) {
          sidebar.addEventListener('click', function (event) {
              if (modeHp() && event.target.closest('a')) {
                  tutupLaci();
              }
          });
      }

      document.addEventListener('keydown', function (event) {
          if (event.key === 'Escape') {
              tutupLaci();
          }
      });

      // Kalau layar dibesarkan saat laci terbuka, kembalikan ke mode desktop.
      function tanganiPerubahanLayar(event) {
          if (!event.matches) {
              tutupLaci();
          }
      }

      if (typeof mediaHp.addEventListener === 'function') {
          mediaHp.addEventListener('change', tanganiPerubahanLayar);
      } else if (typeof mediaHp.addListener === 'function') {
          // Browser lama (Android WebView / Safari lama)
          mediaHp.addListener(tanganiPerubahanLayar);
      }
  })();
</script>
</body>
</html>
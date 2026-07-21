<script>
    // ── TAB SWITCHER (Kategori / Bahan Baku / Produk / Resep / User) ──
    document.querySelectorAll('.md-tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const target = this.getAttribute('data-tab');

            document.querySelectorAll('.md-tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.md-tab-panel').forEach(panel => {
                panel.classList.toggle('active', panel.getAttribute('data-panel') === target);
            });

            // Simpan tab aktif di URL hash supaya tidak hilang saat reload (mis. setelah submit form)
            history.replaceState(null, '', '#' + target);
        });
    });

    // Buka tab sesuai hash URL saat halaman dimuat (berguna setelah redirect dari form submit)
    document.addEventListener('DOMContentLoaded', function () {
        const hash = window.location.hash.replace('#', '');
        if (hash) {
            const btn = document.querySelector('.md-tab-btn[data-tab="' + hash + '"]');
            if (btn) btn.click();
        }
    });

    // ── ADD/REMOVE BARIS RESEP DI MODAL TAMBAH PRODUK ──
    const mdAddIngredientBtn = document.getElementById('md-add-ingredient');
    if (mdAddIngredientBtn) {
        mdAddIngredientBtn.addEventListener('click', function () {
            const container = document.getElementById('md-resep-container');
            const row = container.querySelector('.md-resep-row').cloneNode(true);
            row.querySelector('select').value = '';
            row.querySelector('input').value = '';
            container.appendChild(row);
        });
    }

    document.addEventListener('click', function (e) {
        if (e.target.closest('.md-remove-resep')) {
            const rows = document.querySelectorAll('.md-resep-row');
            if (rows.length > 1) e.target.closest('.md-resep-row').remove();
        }
    });

    // ── KLIK BARIS KATEGORI → PINDAH KE TAB PRODUK + FILTER ──
    document.querySelectorAll('.md-cat-row').forEach(row => {
        row.addEventListener('click', function () {
            const catId = this.getAttribute('data-category-id');

            // Pindah ke tab Produk
            const tabBtn = document.querySelector('.md-tab-btn[data-tab="produk"]');
            if (tabBtn) tabBtn.click();

            // Terapkan filter setelah tab aktif
            mdFilterByCategory('produk', catId);

            // Sinkronkan dropdown filter
            const select = document.getElementById('filterKategoriProduk');
            if (select) select.value = catId;
        });
    });

    // ── FUNGSI FILTER KATEGORI (dipakai di tab Produk & Bahan Baku) ──
    function mdFilterByCategory(panelType, categoryId) {
        const tbodyId = panelType === 'produk' ? 'tbodyProduk' : 'tbodyBahan';
        const chipId = panelType === 'produk' ? 'chipFilterProduk' : 'chipFilterBahan';
        const chipTextId = panelType === 'produk' ? 'chipFilterProdukText' : 'chipFilterBahanText';
        const selectId = panelType === 'produk' ? 'filterKategoriProduk' : 'filterKategoriBahan';

        const tbody = document.getElementById(tbodyId);
        if (!tbody) return;

        const rows = tbody.querySelectorAll('.md-data-row');
        const emptyFiltered = tbody.querySelector('.md-empty-filtered');
        let visibleCount = 0;

        rows.forEach(row => {
            const match = !categoryId || row.getAttribute('data-category-id') === String(categoryId);
            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        if (emptyFiltered) {
            emptyFiltered.style.display = (categoryId && visibleCount === 0) ? '' : 'none';
        }

        // Update chip & dropdown
        const chip = document.getElementById(chipId);
        const select = document.getElementById(selectId);

        if (categoryId) {
            const selectedOption = select ? select.querySelector('option[value="' + categoryId + '"]') : null;
            const catName = selectedOption ? selectedOption.textContent : '';
            if (chip) {
                chip.classList.add('show');
                document.getElementById(chipTextId).textContent = 'Kategori: ' + catName;
            }
        } else {
            if (chip) chip.classList.remove('show');
        }

        if (select) select.value = categoryId || '';
    }

    // ── CLEAR ZERO ON FOCUS (semua input number di halaman ini) ──
    document.addEventListener('focusin', function (e) {
        if (e.target.matches('input[type="number"]') && e.target.value == '0') {
            e.target.value = '';
        }
    });
    document.addEventListener('focusout', function (e) {
        if (e.target.matches('input[type="number"]') && e.target.value === '') {
            e.target.value = '0';
        }
    });
</script>

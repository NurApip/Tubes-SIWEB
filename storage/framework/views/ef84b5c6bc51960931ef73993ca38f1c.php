

<?php $__env->startSection('title', 'Kelola Lapangan - Admin'); ?>

<?php $__env->startSection('active_menu','fields'); ?>

<?php $__env->startSection('admin_content'); ?>
    <div class="mb-10">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Manajemen Data Lapangan</p>
                <h2 class="text-4xl font-black text-gray-800 uppercase italic tracking-tighter">Kelola Lapangan</h2>
            </div>
            <button
                type="button"
                onclick="openTambahModal()"
                class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold text-xs uppercase hover:bg-blue-700 transition shadow-lg shadow-blue-200"
            >
                + Tambah Lapangan
            </button>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-4">Satu pintu untuk data, detail, dan gallery</p>
    </div>

    <?php if(session('success')): ?>
    <div class="mb-6">
        <div class="relative bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-sm">
            <span class="absolute -bottom-2 left-6 w-3 h-3 bg-green-50 border-l border-b border-green-200 transform rotate-45"></span>
            <?php echo e(session('success')); ?>

        </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="mb-6">
        <div class="relative bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-sm">
            <span class="absolute -bottom-2 left-6 w-3 h-3 bg-red-50 border-l border-b border-red-200 transform rotate-45"></span>
            <?php echo e(session('error')); ?>

        </div>
    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-2xl text-xs font-bold">
        <p class="font-black uppercase tracking-widest mb-3">Data lapangan belum bisa disimpan:</p>
        <ul class="list-disc list-inside space-y-1">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white p-4 rounded-[32px] shadow-sm border border-slate-200 group">

                <div class="relative h-48 mb-4 overflow-hidden rounded-[24px]">
                    <?php if(!empty($f->foto)): ?>
                        <img
                            src="<?php echo e(asset('storage/' . $f->foto)); ?>"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                            alt="<?php echo e($f->nama_lapangan); ?>"
                        >
                    <?php else: ?>
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-futbol text-6xl text-gray-300"></i>
                        </div>
                    <?php endif; ?>

                    <div class="absolute top-3 left-3 bg-blue-600 text-white text-[9px] font-black px-3 py-1 rounded-full uppercase">
                        <?php echo e($f->tipe_rumput); ?>

                    </div>
                </div>

                <h4 class="font-black uppercase text-sm mb-1"><?php echo e($f->nama_lapangan); ?></h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase">
                    <?php echo e($f->lokasi); ?> â€¢ Rp <?php echo e(number_format($f->harga_per_jam ?? 0, 0, ',', '.')); ?>/Jam
                </p>

                <?php if($f->galeri->count() > 0): ?>
                    <div class="mt-4 flex gap-2 overflow-x-auto pb-1">
                        <?php $__currentLoopData = $f->galeri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="relative shrink-0">
                                <img src="<?php echo e(asset('storage/' . $foto->path_foto)); ?>" class="w-16 h-14 object-cover rounded-xl border border-slate-200" alt="Galeri <?php echo e($f->nama_lapangan); ?>">
                                <form action="<?php echo e(route('admin.fields.gallery.delete', [$f->lapangan_id, $foto->id])); ?>" method="POST" onsubmit="return confirm('Hapus foto galeri ini?')" class="absolute -top-2 -right-2">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="w-6 h-6 rounded-full bg-red-600 text-white text-[10px] font-black shadow">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="mt-4 text-[10px] text-slate-300 font-black uppercase">Belum ada foto galeri</p>
                <?php endif; ?>

                <div class="flex gap-2 mt-5">
                    <button
                        type="button"
                        onclick="editLapangan('<?php echo e($f->lapangan_id); ?>')"
                        class="flex-1 bg-slate-100 text-slate-400 py-3 rounded-xl font-black text-[10px] uppercase hover:bg-blue-50 hover:text-blue-600 transition"
                    >
                        Edit
                    </button>

                    <form action="/admin/fields/<?php echo e($f->lapangan_id); ?>/delete" method="POST" onsubmit="return confirm('Yakin ingin menghapus lapangan ini?')" class="inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="bg-slate-100 text-red-500 p-3 rounded-xl hover:bg-red-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div id="modalTambah" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-[40px] w-full max-w-xl p-10 shadow-2xl overflow-y-auto max-h-[90vh]">
            <h3 id="modalTitle" class="text-2xl font-black uppercase italic mb-8">Tambah Data Lapangan</h3>

            <form id="modalForm" action="<?php echo e(route('admin.fields.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
                <?php echo csrf_field(); ?>
                <div id="methodContainer"></div>

                <div class="grid grid-cols-2 gap-5">
                    <div class="col-span-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Nama Lapangan</label>
                        <input type="text" name="nama_lapangan" required class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 outline-none focus:border-blue-400 transition" placeholder="Misal: Lapangan Pro A">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Lokasi Arena</label>
                        <select name="lokasi" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 outline-none focus:border-blue-400 transition">
                            <option value="Bandung Pusat">Bandung Pusat</option>
                            <option value="Bandung Timur">Bandung Timur</option>
                            <option value="Bandung Tengah">Bandung Tengah</option>
                            <option value="Bandung Utara">Bandung Utara</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Tipe Rumput</label>
                        <select name="tipe_rumput" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 outline-none focus:border-blue-400 transition">
                            <option value="Sintetis">Rumput Sintetis</option>
                            <option value="Interlock">Interlock</option>
                            <option value="Mat">Vinyl / Mat</option>
                            <option value="Basah">Rumput Alami</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 outline-none focus:border-blue-400 transition" placeholder="Misal: Jl. PHH Mustofa No. 123, Bandung"></textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Link Google Maps</label>
                        <input type="text" name="link_maps" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 outline-none focus:border-blue-400 transition" placeholder="https://maps.app.goo.gl/...">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Harga per Jam</label>
                        <input type="number" name="harga" required class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 outline-none focus:border-blue-400 transition" placeholder="Rupiah">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Upload Foto</label>
                        <input type="file" name="foto" id="fotoInput" required accept="image/jpeg,image/png,image/webp" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 outline-none focus:border-blue-400 transition">
                    </div>

                    <div class="col-span-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Upload Foto Galeri</label>
                        <input type="file" name="foto_galeri[]" id="fotoGaleriInput" multiple accept="image/jpeg,image/png,image/webp" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 outline-none focus:border-blue-400 transition">
                        <p class="text-[10px] text-slate-400 mt-2 ml-2">Pilih beberapa foto sekaligus untuk ditampilkan di detail lapangan.</p>
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Fasilitas (Enter/Koma = Tag)</label>

                    <input type="hidden" name="fasilitas" id="fasilitasHidden" value="">

                    <div class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 outline-none focus-within:border-blue-400 transition">
                        <div id="fasilitasTags" class="flex flex-wrap gap-2 mb-2"></div>
                        <input
                            id="fasilitasInput"
                            type="text"
                            placeholder="Misal: Kantin (Enter)"
                            class="w-full bg-transparent border-none outline-none text-xs font-black text-slate-800 placeholder:text-slate-400"
                            autocomplete="off"
                        >
                        <p class="text-[10px] text-slate-400 mt-2">Tekan Enter atau koma untuk menambah fasilitas.</p>
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Deskripsi</label>
                    <textarea name="deskripsi" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 outline-none focus:border-blue-400 transition" placeholder="Penjelasan tambahan..."></textarea>
                </div>

                <div class="col-span-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-2" style="pointer-events:auto; cursor:pointer;">Status Aktif Arena</label>
                    <div class="mt-3 flex items-center gap-3 z-[1000] relative pointer-events-auto" style="pointer-events:auto;">
                        <input type="hidden" name="is_active" value="0">

                        <label class="inline-flex items-center cursor-pointer select-none" style="pointer-events:auto;" for="isActiveToggle">
                            <input id="isActiveToggle" type="checkbox" name="is_active" value="1" class="mr-3" checked>
                            <span class="text-xs font-black uppercase text-slate-600">Aktif</span>
                        </label>



                    </div>
                </div>


                <div class="flex gap-4 pt-6">
                    <button
                        type="button"
                        onclick="document.getElementById('modalTambah').classList.add('hidden')"
                        class="flex-1 bg-slate-100 py-4 rounded-2xl font-black uppercase text-xs tracking-widest transition hover:bg-slate-200"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-blue-200 transition hover:bg-blue-700"
                    >
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalTambah');
        const form = document.getElementById('modalForm');
        const methodContainer = document.getElementById('methodContainer');
        const fotoInput = document.getElementById('fotoInput');

        const fasilitasInput = document.getElementById('fasilitasInput');
        const fasilitasHidden = document.getElementById('fasilitasHidden');
        const fasilitasTags = document.getElementById('fasilitasTags');

        const isActiveToggle = document.getElementById('isActiveToggle');

        let fasilitasList = [];

        function syncFasilitasHidden() {
            fasilitasHidden.value = fasilitasList.join(',');
        }

        function renderFasilitasTags() {
            if (!fasilitasTags) return;

            fasilitasTags.innerHTML = '';
            fasilitasList.forEach((item, idx) => {
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center gap-2 bg-slate-100 border border-slate-200 text-slate-700 px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest';

                const text = document.createElement('span');
                text.textContent = item;

                const x = document.createElement('button');
                x.type = 'button';
                x.className = 'text-red-500 hover:text-red-700';
                x.innerHTML = '&times;';
                x.onclick = () => {
                    fasilitasList.splice(idx, 1);
                    renderFasilitasTags();
                    syncFasilitasHidden();
                };

                badge.appendChild(text);
                badge.appendChild(x);
                fasilitasTags.appendChild(badge);
            });
        }

        function addFasilitas(raw) {
            if (!raw) return;
            const item = String(raw).trim();
            if (!item) return;

            const exists = fasilitasList.some(x => x.toLowerCase() === item.toLowerCase());
            if (exists) return;

            fasilitasList.push(item);
            renderFasilitasTags();
            syncFasilitasHidden();
        }

        function addFromInputValue() {
            if (!fasilitasInput) return;
            const value = fasilitasInput.value;
            if (!value) return;

            // Support pisah koma juga
            const parts = value.split(',');
            parts.forEach(p => addFasilitas(p));
            fasilitasInput.value = '';
        }

        if (fasilitasInput) {
            fasilitasInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    addFromInputValue();
                }
            });
        }

        function resetFasilitasUI() {
            fasilitasList = [];
            if (fasilitasTags) fasilitasTags.innerHTML = '';
            if (fasilitasHidden) fasilitasHidden.value = '';
            if (fasilitasInput) fasilitasInput.value = '';
        }

        function fillFasilitasFromBackend(raw) {
            resetFasilitasUI();
            const val = String(raw ?? '').trim();
            if (!val) return;

            const normalized = val.replace(/\r\n/g, ',').replace(/\n/g, ',');
            normalized.split(',').forEach(p => addFasilitas(p));
        }

        function ensureIsActiveHiddenMatchesCheckbox() {
            // hidden is_active value is what backend should receive if checkbox unchecked
            const hidden = form.querySelector('input[name="is_active"][type="hidden"]');
            if (hidden) hidden.value = '0';
        }

        if (isActiveToggle) {
            // debug: pastikan event terpanggil saat user klik
            isActiveToggle.addEventListener('click', () => {
                console.log('is_active toggle clicked', isActiveToggle.checked);
                ensureIsActiveHiddenMatchesCheckbox();
                refreshIsActiveUI();
            });
            isActiveToggle.addEventListener('change', () => {
                ensureIsActiveHiddenMatchesCheckbox();
                refreshIsActiveUI();
            });
        }

        function refreshIsActiveUI() {
            if (!isActiveToggle) return;

            const isActive = !!isActiveToggle.checked;
            const textEl = document.getElementById('isActiveText');
            const trackEl = document.getElementById('isActiveTrack');
            const thumbEl = document.getElementById('isActiveThumb');

            if (textEl) textEl.textContent = isActive ? 'Aktif' : 'Non Aktif';

            // warna sederhana biar jelas berubah
            if (trackEl) {
                trackEl.style.backgroundColor = isActive ? '#1d4ed8' : '#e5e7eb'; // blue-700 / gray-200
            }
            if (thumbEl) {
                thumbEl.style.transform = isActive ? 'translateX(20px)' : 'translateX(0px)';
            }
        }

        function toggleIsActive() {
            if (!isActiveToggle) return;

            // flip
            isActiveToggle.checked = !isActiveToggle.checked;
            ensureIsActiveHiddenMatchesCheckbox();
            refreshIsActiveUI();
        }



        function openTambahModal() {
            document.getElementById('modalTitle').innerText = 'Tambah Data Lapangan';
            form.action = "<?php echo e(route('admin.fields.store')); ?>";
            methodContainer.innerHTML = '';
            form.reset();
            fotoInput.setAttribute('required', 'required');
            modal.classList.remove('hidden');

            // default aktif
            if (isActiveToggle) isActiveToggle.checked = true;
            ensureIsActiveHiddenMatchesCheckbox();
            resetFasilitasUI();
        }

        function editLapangan(id) {
            if (!id) return;

            fetch('/admin/fields/' + id + '/edit')
                .then(response => {
                    if (!response.ok) throw new Error('Gagal mengambil data');
                    return response.json();
                })
                .then(data => {
                    document.getElementById('modalTitle').innerText = 'Edit Data Lapangan';
                    form.action = '/admin/fields/' + id + '/update';

                    methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                                        const fields = {
                        'nama_lapangan': data.nama_lapangan ?? '',
                        'lokasi': data.lokasi ?? 'Bandung Pusat',
                        'alamat_lengkap': data.alamat_lengkap ?? '',
                        'link_maps': data.link_maps ?? '',
                        'latitude': data.latitude ?? '',
                        'longitude': data.longitude ?? '',
                        'tipe_rumput': data.tipe_rumput ?? 'Sintetis',
                        'harga': data.harga_per_jam ?? 0,
                        'fasilitas': data.fasilitas ?? '',
                        'deskripsi': data.deskripsi ?? ''
                    };

                    if (isActiveToggle) {
                        const activeRaw = data.is_active;
                        const active = !(activeRaw === 0 || activeRaw === '0' || activeRaw === false || activeRaw === null || activeRaw === undefined || activeRaw === 'false');
                        isActiveToggle.checked = active;
                    }
                    ensureIsActiveHiddenMatchesCheckbox();

                    Object.keys(fields).forEach(key => {
                        const el = form.querySelector(`[name="${key}"]`);
                        if (el) el.value = fields[key];
                    });

                    fillFasilitasFromBackend(data.fasilitas);

                    if (fotoInput) fotoInput.removeAttribute('required');
                    modal.classList.remove('hidden');
                })
                .catch(err => alert('Error: ' + err.message));
        }
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\FutsalHub\resources\views/admin/gallery/index.blade.php ENDPATH**/ ?>
@extends('layouts.admin')

@section('title', 'Kelola Lapangan - Admin')

@section('active_menu','fields')

@section('admin_content')
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
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-4">Kelola data, status aktif, dan galeri lapangan</p>
    </div>

    @if(session('success'))
    <div class="mb-6">
        <div class="relative bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-sm">
            <span class="absolute -bottom-2 left-6 w-3 h-3 bg-green-50 border-l border-b border-green-200 transform rotate-45"></span>
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6">
        <div class="relative bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-sm">
            <span class="absolute -bottom-2 left-6 w-3 h-3 bg-red-50 border-l border-b border-red-200 transform rotate-45"></span>
            {{ session('error') }}
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-2xl text-xs font-bold">
        <p class="font-black uppercase tracking-widest mb-3">Data lapangan belum bisa disimpan:</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="overflow-x-auto pb-5">
        <div class="flex w-max gap-6 snap-x snap-mandatory">
        @foreach($fields as $f)
        <div class="w-[320px] md:w-[360px] shrink-0 snap-start bg-white p-4 rounded-[32px] shadow-sm border border-slate-200 group">

                <div class="relative h-48 mb-4 overflow-hidden rounded-[24px]">
                    @if(!empty($f->foto))
                        <img
                            src="{{ asset('storage/' . $f->foto) }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                            alt="{{ $f->nama_lapangan }}"
                        >
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-futbol text-6xl text-gray-300"></i>
                        </div>
                    @endif

                    <div class="absolute top-3 left-3 bg-blue-600 text-white text-[9px] font-black px-3 py-1 rounded-full uppercase">
                        {{ $f->tipe_rumput }}
                    </div>
                    <div class="absolute top-3 right-3 {{ $f->is_active ? 'bg-green-600' : 'bg-red-600' }} text-white text-[9px] font-black px-3 py-1 rounded-full uppercase">
                        {{ $f->is_active ? 'Aktif' : 'Nonaktif' }}
                    </div>
                </div>

                <h4 class="font-black uppercase text-sm mb-1">{{ $f->nama_lapangan }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase">
                    {{ $f->lokasi }} - Rp {{ number_format($f->harga_per_jam ?? 0, 0, ',', '.') }}/Jam
                </p>

                <div class="mt-4">
                    <p class="mb-2 text-[9px] text-slate-400 font-black uppercase">Foto Tambahan</p>
                    @php $galeriPerSlot = $f->galeri->keyBy('position'); @endphp
                    <div class="grid grid-cols-4 gap-2">
                        @for($galleryIndex = 0; $galleryIndex < 4; $galleryIndex++)
                            @php $foto = $galeriPerSlot->get($galleryIndex + 1); @endphp
                            @if($foto)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $foto->path_foto) }}" class="w-full h-14 object-cover rounded-xl border border-slate-200" alt="Galeri {{ $f->nama_lapangan }}">
                                <form action="{{ route('admin.fields.gallery.delete', [$f->lapangan_id, $foto->id]) }}" method="POST" onsubmit="return confirm('Hapus foto galeri ini?')" class="absolute -top-2 -right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-6 h-6 rounded-full bg-red-600 text-white text-[10px] font-black shadow">
                                        &times;
                                    </button>
                                </form>
                            </div>
                            @else
                            <div class="h-14 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center text-slate-300">
                                <i class="fas fa-image text-sm"></i>
                            </div>
                            @endif
                        @endfor
                    </div>
                </div>

                <div class="flex gap-2 mt-4">
                    <button
                        type="button"
                        onclick="editLapangan('{{ $f->lapangan_id }}')"
                        class="flex-1 bg-slate-100 text-slate-400 py-3 rounded-xl font-black text-[10px] uppercase hover:bg-blue-50 hover:text-blue-600 transition"
                    >
                        Edit
                    </button>

                    <form action="{{ route('admin.fields.status', $f->lapangan_id) }}" method="POST" class="flex-1" onsubmit="return confirm('{{ $f->is_active ? 'Nonaktifkan lapangan ini?' : 'Aktifkan kembali lapangan ini?' }}')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="{{ $f->is_active ? 0 : 1 }}">
                        <button type="submit" class="w-full {{ $f->is_active ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }} py-3 rounded-xl font-black text-[10px] uppercase transition">
                            {{ $f->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
        </div>
    </div>

    <div id="modalTambah" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-[40px] w-full max-w-xl p-10 shadow-2xl overflow-y-auto max-h-[90vh]">
            <h3 id="modalTitle" class="text-2xl font-black uppercase italic mb-8">Tambah Data Lapangan</h3>

            <form id="modalForm" action="{{ route('admin.fields.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div id="methodContainer"></div>

                <div class="grid grid-cols-2 gap-5">
                    <div class="col-span-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Nama Lapangan</label>
                        <input type="text" name="nama_lapangan" required class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 outline-none focus:border-blue-400 transition" placeholder="Misal: Lapangan Pro A">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Pilih Area</label>
                        <select name="lokasi" required class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 outline-none focus:border-blue-400 transition">
                            <option value="" disabled>Semua Area</option>
                            <option value="Bandung Pusat">Bandung Pusat</option>
                            <option value="Bandung Utara">Bandung Utara</option>
                            <option value="Bandung Selatan">Bandung Selatan</option>
                            <option value="Bandung Timur">Bandung Timur</option>
                            <option value="Bandung Barat">Bandung Barat</option>
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
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Foto Utama</label>
                        <input type="file" name="foto" id="fotoInput" required accept="image/jpeg,image/png,image/webp" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 outline-none focus:border-blue-400 transition">
                    </div>

                    <div class="col-span-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Foto Tambahan</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
                            @for($slot = 1; $slot <= 4; $slot++)
                                <div>
                                    <label class="block text-[9px] font-black uppercase text-slate-400 ml-2 mb-1">Foto Tambahan {{ $slot }}</label>
                                    <input type="file" name="foto_galeri[{{ $slot - 1 }}]" accept="image/jpeg,image/png,image/webp" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-3 outline-none focus:border-blue-400 transition text-xs">
                                </div>
                            @endfor
                        </div>
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

        function openTambahModal() {
            document.getElementById('modalTitle').innerText = 'Tambah Data Lapangan';
            form.action = "{{ route('admin.fields.store') }}";
            methodContainer.innerHTML = '';
            form.reset();
            fotoInput.setAttribute('required', 'required');
            modal.classList.remove('hidden');

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
@endsection


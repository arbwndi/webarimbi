<x-appadmin-layout>
    <div class="container mt-4">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Manajemen Kategori</h4>
            <button onclick="showModal('modalTambah')" class="btn btn-primary">
                + Tambah Kategori
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode Kategori</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $i => $category)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><code>{{ $category->kode_kategori }}</code></td>
                            <td>{{ $category->nama_kategori }}</td>
                            <td>{{ $category->deskripsi ?? '-' }}</td>
                            <td>
                                <button onclick="openEdit(
                                    {{ $category->id }},
                                    '{{ $category->kode_kategori }}',
                                    '{{ addslashes($category->nama_kategori) }}',
                                    '{{ addslashes($category->deskripsi ?? '') }}')"
                                    class="btn btn-sm btn-warning">
                                    ✏️ Edit
                                </button>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                      style="display:inline;"
                                      onsubmit="return confirm('Yakin hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada data kategori.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="modalTambah" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:50;align-items:center;justify-content:center;">
        <div style="background:white;border-radius:12px;padding:24px;width:480px;max-width:90vw;">
            <h5 class="mb-3">🗂️ Tambah Kategori Baru</h5>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                @include('admin.categories._form')
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" onclick="hideModal('modalTambah')" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="modalEdit" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:50;align-items:center;justify-content:center;">
        <div style="background:white;border-radius:12px;padding:24px;width:480px;max-width:90vw;">
            <h5 class="mb-3">✏️ Edit Kategori</h5>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                @include('admin.categories._form')
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" onclick="hideModal('modalEdit')" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function showModal(id) {
        document.getElementById(id).style.display = 'flex';
    }
    function hideModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    function openEdit(id, kode, nama, deskripsi) {
        const form = document.getElementById('formEdit');
        form.action = '/admin/categories/' + id;
        form.querySelector('[name=kode_kategori]').value = kode;
        form.querySelector('[name=nama_kategori]').value = nama;
        form.querySelector('[name=deskripsi]').value = deskripsi;
        showModal('modalEdit');
    }
    ['modalTambah', 'modalEdit'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) hideModal(id);
        });
    });
    @if($errors->any())
        @if(old('_method') === 'PUT')
            showModal('modalEdit');
        @else
            showModal('modalTambah');
        @endif
    @endif
    </script>

</x-appadmin-layout>
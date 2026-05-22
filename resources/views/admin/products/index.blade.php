<x-appadmin-layout>
    <div class="container mt-4">

        {{-- Flash message --}}
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Manajemen Produk</h1>
            <button onclick="showModal('modalTambah')" class="btn btn-primary">
                + Tambah Produk
            </button>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $i => $product)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><code>{{ $product->kode_barang }}</code></td>
                            <td>{{ $product->nama_barang }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $product->satuan }}</span>
                            </td>
                            <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                            <td>
                                <button onclick="openEdit({{ $product->id }}, '{{ $product->kode_barang }}', '{{ addslashes($product->nama_barang) }}', '{{ $product->satuan }}', '{{ $product->harga }}')"
                                    class="btn btn-sm btn-warning">
                                    ✏️ Edit
                                </button>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline;"
                                      onsubmit="return confirm('Yakin hapus produk ini?')">
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
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data produk. Klik "Tambah Produk" untuk memulai.
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
        <div style="background:white;border-radius:12px;padding:24px;width:420px;max-width:90vw;">
            <h5 class="mb-3">📦 Tambah Produk Baru</h5>
            <form method="POST" action="{{ route('admin.products.store') }}">
                @csrf
                @include('admin.products._form')
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" onclick="hideModal('modalTambah')" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="modalEdit" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:50;align-items:center;justify-content:center;">
        <div style="background:white;border-radius:12px;padding:24px;width:420px;max-width:90vw;">
            <h5 class="mb-3">✏️ Edit Produk</h5>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                @include('admin.products._form')
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
    function openEdit(id, kode, nama, satuan, harga) {
        const form = document.getElementById('formEdit');
        form.action = '/admin/products/' + id;
        form.querySelector('[name=kode_barang]').value = kode;
        form.querySelector('[name=nama_barang]').value = nama;
        form.querySelector('[name=satuan]').value = satuan;
        form.querySelector('[name=harga]').value = harga;
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
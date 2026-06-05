<x-appadmin-layout>
    <div class="container mt-4">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>List Customer</h4>
            <button onclick="showModal('modalTambah')" class="btn btn-primary">
                + Tambah Customer
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th>Alamat</th>
                            <th>Email</th>
                            <th>No. Telp</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $i => $customer)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $customer->kode_customer }}</td>
                            <td>{{ $customer->nama_customer }}</td>
                            <td>{{ $customer->alamat }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->no_telp }}</td>
                            <td>
                                <button onclick="openEdit(
                                    {{ $customer->id }},
                                    '{{ $customer->kode_customer }}',
                                    '{{ addslashes($customer->nama_customer) }}',
                                    '{{ addslashes($customer->alamat) }}',
                                    '{{ $customer->email }}',
                                    '{{ $customer->no_telp }}')"
                                    class="btn btn-sm btn-warning">
                                    ✏️ Edit
                                </button>
                                <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}"
                                      style="display:inline;"
                                      onsubmit="return confirm('Yakin hapus customer ini?')">
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
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada data customer.
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
            <h5 class="mb-3">👤 Tambah Customer Baru</h5>
            <form method="POST" action="{{ route('admin.customers.store') }}">
                @csrf
                @include('admin.customers._form')
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
            <h5 class="mb-3">✏️ Edit Customer</h5>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                @include('admin.customers._form')
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
    function openEdit(id, kode, nama, alamat, email, no_telp) {
        const form = document.getElementById('formEdit');
        form.action = '/admin/customers/' + id;
        form.querySelector('[name=kode_customer]').value = kode;
        form.querySelector('[name=nama_customer]').value = nama;
        form.querySelector('[name=alamat]').value = alamat;
        form.querySelector('[name=email]').value = email;
        form.querySelector('[name=no_telp]').value = no_telp;
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
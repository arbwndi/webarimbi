<div class="mb-3">
    <label class="form-label">Kode Barang</label>
    <input name="kode_barang" type="text"
        value="{{ old('kode_barang') }}"
        class="form-control" placeholder="cth: BRG-001">
    @error('kode_barang')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Nama Barang</label>
    <input name="nama_barang" type="text"
        value="{{ old('nama_barang') }}"
        class="form-control" placeholder="Nama lengkap barang">
    @error('nama_barang')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Kategori</label>
    <select name="category_id" class="form-select">
        <option value="">-- Pilih Kategori --</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->nama_kategori }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-6">
        <label class="form-label">Satuan</label>
        <select name="satuan" class="form-select">
            @foreach(['Pcs','Box','Kg','Liter','Lusin','Rim','Set','Unit'] as $s)
                <option value="{{ $s }}"
                    {{ old('satuan') === $s ? 'selected' : '' }}>
                    {{ $s }}
                </option>
            @endforeach
        </select>
        @error('satuan')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-6">
        <label class="form-label">Harga (Rp)</label>
        <input name="harga" type="number" min="0"
            value="{{ old('harga') }}"
            class="form-control" placeholder="0">
        @error('harga')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>
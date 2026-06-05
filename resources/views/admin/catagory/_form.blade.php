<div class="mb-3">
    <label class="form-label">Kode Kategori</label>
    <input name="kode_kategori" type="text"
        value="{{ old('kode_kategori', $category->kode_kategori ?? '') }}"
        class="form-control" placeholder="cth: KAT-001">
    @error('kode_kategori')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Nama Kategori</label>
    <input name="nama_kategori" type="text"
        value="{{ old('nama_kategori', $category->nama_kategori ?? '') }}"
        class="form-control" placeholder="Nama kategori">
    @error('nama_kategori')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi <span class="text-muted">(opsional)</span></label>
    <textarea name="deskripsi" class="form-control" rows="3"
        placeholder="Deskripsi kategori">{{ old('deskripsi', $category->deskripsi ?? '') }}</textarea>
    @error('deskripsi')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>
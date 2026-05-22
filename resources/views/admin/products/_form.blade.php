<div style="margin-bottom:14px;">
    <label style="display:block;font-size:12px;font-weight:500;color:#6b7280;margin-bottom:5px;text-transform:uppercase;">
        Kode Barang
    </label>
    <input name="kode_barang" type="text"
        value="{{ old('kode_barang', $product->kode_barang ?? '') }}"
        style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;"
        placeholder="cth: BRG-001">
    @error('kode_barang')
        <p style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</p>
    @enderror
</div>

<div style="margin-bottom:14px;">
    <label style="display:block;font-size:12px;font-weight:500;color:#6b7280;margin-bottom:5px;text-transform:uppercase;">
        Nama Barang
    </label>
    <input name="nama_barang" type="text"
        value="{{ old('nama_barang', $product->nama_barang ?? '') }}"
        style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;"
        placeholder="Nama lengkap barang">
    @error('nama_barang')
        <p style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</p>
    @enderror
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
    <div>
        <label style="display:block;font-size:12px;font-weight:500;color:#6b7280;margin-bottom:5px;text-transform:uppercase;">
            Satuan
        </label>
        <select name="satuan"
            style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;background:white;">
            @foreach(['Pcs','Box','Kg','Liter','Lusin','Rim','Set','Unit'] as $s)
                <option value="{{ $s }}"
                    {{ old('satuan', $product->satuan ?? '') === $s ? 'selected' : '' }}>
                    {{ $s }}
                </option>
            @endforeach
        </select>
        @error('satuan')
            <p style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label style="display:block;font-size:12px;font-weight:500;color:#6b7280;margin-bottom:5px;text-transform:uppercase;">
            Harga (Rp)
        </label>
        <input name="harga" type="number" min="0" step="1"
            value="{{ old('harga', $product->harga ?? '') }}"
            style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;"
            placeholder="0">
        @error('harga')
            <p style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</p>
        @enderror
    </div>
</div>
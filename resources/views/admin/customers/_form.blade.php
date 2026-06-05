<div class="mb-3">
    <label class="form-label">Kode Customer</label>
    <input name="kode_customer" type="text"
        value="{{ old('kode_customer', $customer->kode_customer ?? '') }}"
        class="form-control" placeholder="cth: CUST-001">
    @error('kode_customer')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Nama Customer</label>
    <input name="nama_customer" type="text"
        value="{{ old('nama_customer', $customer->nama_customer ?? '') }}"
        class="form-control" placeholder="Nama lengkap customer">
    @error('nama_customer')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="alamat" class="form-control" rows="3"
        placeholder="Alamat lengkap">{{ old('alamat', $customer->alamat ?? '') }}</textarea>
    @error('alamat')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Email</label>
    <input name="email" type="email"
        value="{{ old('email', $customer->email ?? '') }}"
        class="form-control" placeholder="email@example.com">
    @error('email')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">No. Telepon</label>
    <input name="no_telp" type="text"
        value="{{ old('no_telp', $customer->no_telp ?? '') }}"
        class="form-control" placeholder="08xxxxxxxxxx">
    @error('no_telp')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>
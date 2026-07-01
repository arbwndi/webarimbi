@php
    $selectedCustomerId = old('customer_id', isset($sale) ? $sale->customer_id : '');
    $formItems = old(
        'items',
        isset($sale)
            ? $sale->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                ];
            })->toArray()
            : [['product_id' => '', 'qty' => 1]]
    );
@endphp

<form action="{{ $action }}" method="POST">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="mb-3">
        <label for="customer_id" class="form-label">Customer</label>
        <select class="form-select" id="customer_id" name="customer_id" required>
            <option value="">Pilih Customer</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" @selected((string) $selectedCustomerId === (string) $customer->id)>
                    {{ $customer->kode }} - {{ $customer->nama }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="sale_date" class="form-label">Tanggal Transaksi</label>
        <input
            type="date"
            class="form-control"
            id="sale_date"
            name="sale_date"
            value="{{ old('sale_date', isset($sale) ? optional($sale->sale_date)->format('Y-m-d') : now()->format('Y-m-d')) }}"
            required
        >
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Detail Item</h5>
        <button type="button" class="btn btn-outline-secondary" id="add-sale-item">Tambah Item</button>
    </div>

    <div id="sale-items">
        @foreach ($formItems as $index => $item)
            <div class="row g-3 align-items-end sale-item-row mb-3">
                <div class="col-md-8">
                    <label class="form-label">Produk</label>
                    <select class="form-select" name="items[{{ $index }}][product_id]" required>
                        <option value="">Pilih Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected((string) ($item['product_id'] ?? '') === (string) $product->id)>
                                {{ $product->nama }} - Rp {{ number_format($product->harga, 0, ',', '.') }} (Stok: {{ $product->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Qty</label>
                    <input type="number" class="form-control" name="items[{{ $index }}][qty]" min="1" value="{{ $item['qty'] ?? 1 }}" required>
                </div>
                <div class="col-md-1 d-grid">
                    <button type="button" class="btn btn-outline-danger remove-sale-item">X</button>
                </div>
            </div>
        @endforeach
    </div>

    <template id="sale-item-template">
        <div class="row g-3 align-items-end sale-item-row mb-3">
            <div class="col-md-8">
                <label class="form-label">Produk</label>
                <select class="form-select" name="items[__INDEX__][product_id]" required>
                    <option value="">Pilih Produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->nama }} - Rp {{ number_format($product->harga, 0, ',', '.') }} (Stok: {{ $product->stok }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Qty</label>
                <input type="number" class="form-control" name="items[__INDEX__][qty]" min="1" value="1" required>
            </div>
            <div class="col-md-1 d-grid">
                <button type="button" class="btn btn-outline-danger remove-sale-item">X</button>
            </div>
        </div>
    </template>

    <button type="submit" class="btn btn-primary">{{ $buttonLabel }}</button>
</form>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const saleItems = document.getElementById('sale-items');
                const template = document.getElementById('sale-item-template');
                const addButton = document.getElementById('add-sale-item');

                if (!saleItems || !template || !addButton) {
                    return;
                }

                const reindexSaleItems = () => {
                    const rows = saleItems.querySelectorAll('.sale-item-row');
                    rows.forEach(function (row, index) {
                        const productSelect = row.querySelector('select[name*="[product_id]"]');
                        const qtyInput = row.querySelector('input[name*="[qty]"]');

                        if (productSelect) {
                            productSelect.name = `items[${index}][product_id]`;
                        }
                        if (qtyInput) {
                            qtyInput.name = `items[${index}][qty]`;
                        }
                    });
                };

                addButton.addEventListener('click', function () {
                    saleItems.appendChild(template.content.cloneNode(true));
                    reindexSaleItems();
                });

                saleItems.addEventListener('click', function (event) {
                    const removeButton = event.target.closest('.remove-sale-item');
                    if (!removeButton) {
                        return;
                    }

                    const rows = saleItems.querySelectorAll('.sale-item-row');
                    if (rows.length > 1) {
                        removeButton.closest('.sale-item-row').remove();
                        reindexSaleItems();
                    }
                });

                reindexSaleItems();
            });
        </script>
    @endpush
@endonce
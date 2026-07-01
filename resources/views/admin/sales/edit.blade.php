<x-appadmin-layout>
    <div class="container mt-5">
        <h1>Edit Sale</h1>
        @include('admin.sales._form', [
            'sale' => $sale,
            'action' => route('sales.update', $sale->id),
            'method' => 'PUT',
            'buttonLabel' => 'Update Sale',
            'customers' => $customers,
            'products' => $products,
        ])
    </div>
</x-appadmin-layout>
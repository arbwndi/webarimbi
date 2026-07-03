<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function index()
    {
        return view('admin.sales.index', [
            'sales' => Sale::with('customer', 'items.product')->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.sales.create', [
            'customers' => Customer::orderBy('nama_customer', 'asc')->get(),
            'products' => Product::orderBy('nama_barang', 'asc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateSale($request);
        $items = $this->normalizeItems($validated['items']);

        if ($items === []) {
            return back()
                ->withInput()
                ->withErrors(['items' => 'Minimal satu item penjualan harus diisi.']);
        }

        DB::transaction(function () use ($validated, $items) {
            $products = $this->lockProducts(collect($items)->pluck('product_id')->all());

            $sale = Sale::create([
                'kode' => $this->generateSaleCode(),
                'customer_id' => $validated['customer_id'],
                'sale_date' => $validated['sale_date'],
                'total_amount' => 0,
            ]);

            $totalAmount = 0;
            $saleItems = [];

            foreach ($items as $item) {
                $product = $products->get($item['product_id']);
                $subtotal = round((float) $product->harga * $item['qty'], 2);

                $saleItems[] = [
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $product->harga,
                    'subtotal' => $subtotal,
                ];

                $totalAmount += $subtotal;
            }

            $sale->update(['total_amount' => $totalAmount]);
            $sale->items()->createMany($saleItems);
        });

        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
    }

    public function show(int $id)
    {
        $sale = Sale::with('customer', 'items.product')->findOrFail($id);

        return view('admin.sales.show', compact('sale'));
    }

    public function edit(int $id)
    {
        $sale = Sale::with('items.product')->findOrFail($id);

        return view('admin.sales.edit', [
            'sale' => $sale,
            'customers' => Customer::orderBy('nama_customer', 'asc')->get(),
            'products' => Product::orderBy('nama_barang', 'asc')->get(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $sale = Sale::with('items')->findOrFail($id);
        $validated = $this->validateSale($request);
        $items = $this->normalizeItems($validated['items']);

        if ($items === []) {
            return back()
                ->withInput()
                ->withErrors(['items' => 'Minimal satu item penjualan harus diisi.']);
        }

        DB::transaction(function () use ($sale, $validated, $items) {
            $sale->load('items');

            $productIds = collect($items)
                ->pluck('product_id')
                ->merge($sale->items->pluck('product_id'))
                ->unique()
                ->values()
                ->all();

            $products = $this->lockProducts($productIds);

            $sale->update([
                'customer_id' => $validated['customer_id'],
                'sale_date' => $validated['sale_date'],
            ]);

            $sale->items()->delete();

            $totalAmount = 0;
            $saleItems = [];

            foreach ($items as $item) {
                $product = $products->get($item['product_id']);
                $subtotal = round((float) $product->harga * $item['qty'], 2);

                $saleItems[] = [
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $product->harga,
                    'subtotal' => $subtotal,
                ];

                $totalAmount += $subtotal;
            }

            $sale->update(['total_amount' => $totalAmount]);
            $sale->items()->createMany($saleItems);
        });

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    public function destroy(int $id)
    {
        $sale = Sale::with('items')->findOrFail($id);

        DB::transaction(function () use ($sale) {
            $sale->items()->delete();
            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }

    private function validateSale(Request $request): array
    {
        return $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'sale_date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);
    }

    private function normalizeItems(array $items): array
    {
        return collect($items)
            ->map(function (array $item): array {
                return [
                    'product_id' => (int) ($item['product_id'] ?? 0),
                    'qty' => (int) ($item['qty'] ?? 0),
                ];
            })
            ->filter(function (array $item): bool {
                return $item['product_id'] > 0 && $item['qty'] > 0;
            })
            ->values()
            ->all();
    }

    private function lockProducts(array $productIds): Collection
    {
        return Product::whereIn('id', $productIds)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
    }

    private function generateSaleCode(): string
    {
        return 'TRX-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
    }
}
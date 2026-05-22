<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:50|unique:products,kode_barang',
            'nama_barang' => 'required|string|max:150',
            'satuan'      => 'required|string|max:30',
            'harga'       => 'required|numeric|min:0',
        ], [
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'harga.numeric'      => 'Harga harus berupa angka.',
        ]);

        Product::create($request->only('kode_barang', 'nama_barang', 'satuan', 'harga'));

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:50|unique:products,kode_barang,' . $product->id,
            'nama_barang' => 'required|string|max:150',
            'satuan'      => 'required|string|max:30',
            'harga'       => 'required|numeric|min:0',
        ], [
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
        ]);

        $product->update($request->only('kode_barang', 'nama_barang', 'satuan', 'harga'));

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil dihapus.');
    }
}
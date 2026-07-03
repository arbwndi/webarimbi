<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products   = Product::with('category')->latest()->get();
        $categories = Category::orderBy('nama_kategori')->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'   => 'required|unique:products,kode_barang',
            'nama_barang'   => 'required',
            'satuan'        => 'required',
            'harga'         => 'required|numeric|min:0',
            'category_id'   => 'nullable|exists:categories,id',
        ]);

        Product::create($request->only('kode_barang', 'nama_barang', 'satuan', 'harga', 'category_id'));

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'kode_barang'   => 'required|unique:products,kode_barang,' . $product->id,
            'nama_barang'   => 'required',
            'satuan'        => 'required',
            'harga'         => 'required|numeric|min:0',
            'category_id'   => 'nullable|exists:categories,id',
        ]);

        $product->update($request->only('kode_barang', 'nama_barang', 'satuan', 'harga', 'category_id'));

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
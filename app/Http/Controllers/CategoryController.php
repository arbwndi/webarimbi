<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kategori' => 'required|string|max:50|unique:categories,kode_kategori',
            'nama_kategori' => 'required|string|max:150',
            'deskripsi'     => 'nullable|string',
        ]);

        Category::create($request->only('kode_kategori', 'nama_kategori', 'deskripsi'));

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'kode_kategori' => 'required|string|max:50|unique:categories,kode_kategori,' . $category->id,
            'nama_kategori' => 'required|string|max:150',
            'deskripsi'     => 'nullable|string',
        ]);

        $category->update($request->only('kode_kategori', 'nama_kategori', 'deskripsi'));

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil dihapus.');
    }
}
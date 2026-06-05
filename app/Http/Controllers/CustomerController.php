<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('id', 'desc')->get();
        return view('admin.customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_customer' => 'required|string|unique:customers,kode_customer',
            'nama_customer' => 'required|string|max:150',
            'alamat'        => 'required|string',
            'email'         => 'required|email|unique:customers,email',
            'no_telp'       => 'required|string|max:15',
        ]);

        Customer::create($request->only('kode_customer', 'nama_customer', 'alamat', 'email', 'no_telp'));

        return redirect()->route('admin.customers.index')
                         ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'kode_customer' => 'required|string|unique:customers,kode_customer,' . $customer->id,
            'nama_customer' => 'required|string|max:150',
            'alamat'        => 'required|string',
            'email'         => 'required|email|unique:customers,email,' . $customer->id,
            'no_telp'       => 'required|string|max:15',
        ]);

        $customer->update($request->only('kode_customer', 'nama_customer', 'alamat', 'email', 'no_telp'));

        return redirect()->route('admin.customers.index')
                         ->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('admin.customers.index')
                         ->with('success', 'Customer berhasil dihapus.');
    }
}
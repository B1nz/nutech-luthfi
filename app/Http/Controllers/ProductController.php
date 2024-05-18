<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Open add product page
    public function add()
    {
        $categories = Categories::all();
        return view('product.add', compact('categories'));
    }

    // Proceed store product data
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'nama' => 'required|string|max:255|unique:products', // Ensure nama is unique
            'harga_beli' => 'required|string',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:jpg,png|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Remove non-numeric characters from harga_jual and harga_beli
        $hargaBeli = preg_replace('/[^0-9]/', '', $request->input('harga_beli'));
        $hargaJual = $hargaBeli * 130 / 100;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        Products::create([
            'kategori_id' => $request->category_id,
            'nama' => $request->nama,
            'harga_jual' => $hargaJual,
            'harga_beli' => $hargaBeli,
            'stok' => $request->stock,
            'image_path' => $imagePath,
        ]);

        return redirect('/')->with('success', 'Product added successfully.');
    }

    // Open edit page
    public function edit($id)
    {
        $product = Products::findOrFail($id);
        $categories = Categories::all();

        return view('product.edit', compact('product', 'categories'));
    }

    // Proceed with updating data
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'nama' => 'required|string|max:255|unique:products,nama,' . $id, // Ensure nama is unique, excluding the current product
            'harga_beli' => 'required|string',
            'stock' => 'required|integer',
            'image' => 'image|mimes:jpg,png|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Remove non-numeric characters from harga_jual and harga_beli
        $hargaBeli = preg_replace('/[^0-9]/', '', $request->input('harga_beli'));
        $hargaJual = $hargaBeli * 130 / 100;

        $product = Products::findOrFail($id);
        $product->nama = $request->input('nama');
        $product->kategori_id = $request->input('category_id');
        $product->harga_beli = $hargaBeli;
        $product->harga_jual = $hargaJual;
        $product->stok = $request->input('stock');

        if ($request->hasFile('image')) {
            // Update image only if a new image is uploaded
            $imagePath = $request->file('image')->store('images', 'public');
            $product->image_path = $imagePath;
        }

        $product->save();

        return redirect()->route('products')->with('success', 'Product updated successfully.');
    }

    public function delete($id)
    {
        $product = Products::findOrFail($id);
        $product->delete();

        return response()->json(['success' => 'Product deleted successfully']);
    }
}

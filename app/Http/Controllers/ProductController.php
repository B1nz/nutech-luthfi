<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function add()
    {
        $categories = Categories::all();
        return view('product.product', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
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

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        Products::create([
            'kategori_id' => $request->category_id,
            'nama' => $request->name,
            'harga_jual' => $hargaJual,
            'harga_beli' => $hargaBeli,
            'stok' => $request->stock,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('products.create')->with('success', 'Product added successfully.');
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;

class ExportController extends Controller
{
    /**
     * Export products to Excel.
     *
     * @param  Request  $request
     * @return \Maatwebsite\Excel\Excel
     */
    public function export(Request $request)
    {
        // Get search term and category from the request
        $search = $request->input('search');
        $category = $request->input('category');

        // Filter products based on search term and category
        $products = Products::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            ->when($category, function ($query) use ($category) {
                $query->where('kategori_id', $category);
            })
            ->get();

        // Export filtered products to Excel
        return Excel::download(new ProductsExport($products), 'products.xlsx');
    }
}

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
        $search = $request->input('search');
        $category = $request->input('category');

        $query = Products::query();

        if ($search) {
            $query->whereRaw('nama LIKE ?', ["%{$search}%"]);
        }

        if ($category) {
            $query->where('kategori_id', '=', $category);
        }

        $products = $query->get();

        $timestamp = date('Y-m-d_H-i-s');
        $filename = 'products_' . $timestamp . '.xlsx';

        return Excel::download(new ProductsExport($products), $filename);
    }
}

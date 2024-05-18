<?php

namespace App\Exports;

use App\Models\Products;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductsExport implements FromCollection, WithEvents
{
    public function __construct(private Collection $products)
    {
        //
    }

    public function collection(): Collection
    {
        $collection = collect($this->products)->map(function ($product, $index) {
            return [
                'No' => $index + 1,
                'Name' => $product->nama,
                'Category' => $product->kategori->name, // Category name
                'Harga Beli' => 'Rp ' . number_format($product->harga_beli, 0, ',', '.'), // Format harga_beli
                'Harga Jual' => 'Rp ' . number_format($product->harga_jual, 0, ',', '.'), // Format harga_jual
                'Stok' => $product->stok,
            ];
        });

        // dd($collection);

        return $collection;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Merge cells for the title row
                $event->sheet->mergeCells('A1:F1');
                // Apply styles to the title row
                $event->sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);
                // Set the value for the title row
                $event->sheet->setCellValue('A1', 'DATA PRODUK');

                // Set the value for the headings row
                $event->sheet->setCellValue('A3', 'No');
                $event->sheet->setCellValue('B3', 'Name');
                $event->sheet->setCellValue('C3', 'Category');
                $event->sheet->setCellValue('D3', 'Harga Beli');
                $event->sheet->setCellValue('E3', 'Harga Jual');
                $event->sheet->setCellValue('F3', 'Stok');

                // Apply styles to the range of cells containing headings
                $event->sheet->getStyle('A3:F3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 14,
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'FF0000'],
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);
            },
        ];
    }
}

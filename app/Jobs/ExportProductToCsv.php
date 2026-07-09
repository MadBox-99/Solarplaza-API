<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExportProductToCsv implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $products = Product::get();
        $csvData = [];
        foreach ($products as $product) {
            $csvData[] = [
                'SKU' => $product->ean_code,
                'Name' => $product->name,
                'Price' => $product->price,
                'Stock' => $product->stock,
                'description' => $product->description,
                'image' => $product->image,
                'mechanical_parameters_width' => $product->mechanical_parameters_width,
                'mechanical_parameters_height' => $product->mechanical_parameters_height,
                'mechanical_parameters_thickness' => $product->mechanical_parameters_thickness,
                'mechanical_parameters_weight' => $product->mechanical_parameters_weight,
                'ean_code' => $product->ean_code,
                'document' => $product->document,
            ];
        }
        $csvFileName = 'products.csv';
        $csvFilePath = storage_path('app/' . $csvFileName);

        $file = fopen($csvFilePath, 'w');
        fputcsv($file, [
            'SKU',
            'Name',
            'Price',
            'Stock',
            'description',
            'image',
            'mechanical_parameters_width',
            'mechanical_parameters_height',
            'mechanical_parameters_thickness',
            'mechanical_parameters_weight',
            'ean_code',
            'document'
        ], ";");
        foreach ($csvData as $row) {
            fputcsv($file, $row, ";");
        }
        fclose($file);
    }
}

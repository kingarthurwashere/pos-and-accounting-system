<?php

namespace App\Imports;

use App\Models\StockProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;

use function PHPUnit\Framework\isNull;

class InventoryImport implements ToModel
{

    public function model(array $row)
    {
        if (isset($row[1]) && $row[1] === 'Location') {
            return null; // Skip processing for location rows
        }

        // if (isset($row[4]) && (isNull($row[4]) || $row[4] == '')) {
        //     return null; // Skip processing for location rows
        // }

        // Check duplicates
        if (isset($row[4])) {
            $slug = Str::slug($row[4]);
            $item = StockProduct::where('slug', '=', $slug)->first();
            if ($item) {
                $row[4] = null; // Skip duplicate entries
            }
        }
        if (isset($row[5])) {
            $item = StockProduct::where('sku', '=', $row[5])->first();
            if ($item) {
                return null; // Skip duplicate entries
            }
        }

        // Check if null
        if (!isset($row[8])) {
            return null;
        }
        if (is_null($row[8])) { // Fix typo: use is_null() instead of isNull()
            return null;
        }

        // Create a new StockProduct instance
        $stockProduct = new StockProduct([
            'city_name' => $row[1],
            'initial_stock_taker' => $row[2],
            'category' => $row[3] !== null || strlen($row[3]) > 0 ? Str::slug($row[3]) : null,
            'category_original_name' => $row[3],
            'name' => $row[4],
            'slug' => Str::slug($row[4]) . '_' . uniqid(),
            'sku' => $row[5],
            'color' => $row[6],
            'price' => (int)$row[7],
            'stock_quantity' => (int)$row[8],
            'size' => $row[9],
            'price_approved' => 1,
            'price_approved_by' => 1,
            'location_id' => auth()->user()->login_location_id,
            'uploaded_by' => auth()->user()->id,
        ]);

        // Manually handle the transaction
        try {
            DB::beginTransaction();

            // Save the StockProduct instance
            if ($stockProduct->save()) {
                // Log::info('SAVED');
                // Log::info($stockProduct->toArray());
            } else {
                // Log::error('SAVE FAILED');
            }

            // Other custom logic (if needed)

            DB::commit(); // Commit the transaction
        } catch (\Throwable $e) {

            Log::info('ROLLING BACK');
            Log::info($e->getMessage());
            //DB::rollBack(); // Roll back the transaction on error
            // Handle the error (log, notify, etc.)
            return null;
        }

        return $stockProduct;
    }
}

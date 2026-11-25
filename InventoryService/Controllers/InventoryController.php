<?php

namespace InventoryService\Controllers;

use InventoryService\Models\InventoryBatch;
use Illuminate\Http\Request;

class InventoryController
{
    /**
     * POST /api/inventory - Nhập lô hàng mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fruit_id'    => 'required|integer|min:1',
            'quantity_kg' => 'required|numeric|min:0.1',
            'import_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:import_date',
        ]);

        $batch = InventoryBatch::create($validated);

        return response()->json([
            'message' => 'Nhập lô hàng thành công',
            'data'    => $batch,
        ], 201);
    }

    /**
     * GET /api/inventory?fruit_id=1 - Xem danh sách lô & tính tổng tồn kho
     */
    public function index(Request $request)
    {
        $fruitId = $request->query('fruit_id');

        $query = InventoryBatch::query()->orderBy('import_date', 'asc');

        if ($fruitId !== null && $fruitId !== '') {
            $query->where('fruit_id', (int) $fruitId);
        }

        $batches = $query->get();
        $totalStockKg = round((float) $batches->sum('quantity_kg'), 2);

        return response()->json([
            'fruit_id'       => ($fruitId !== null && $fruitId !== '') ? (int) $fruitId : 'all',
            'total_stock_kg' => $totalStockKg,
            'total_batches'  => $batches->count(),
            'batches'        => $batches,
        ], 200);
    }
}

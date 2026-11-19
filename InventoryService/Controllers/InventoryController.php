<?php

namespace InventoryService\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InventoryService\Models\InventoryBatch;

class InventoryController extends Controller
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

        $query = InventoryBatch::query()
            ->where('quantity_kg', '>', 0) // Chỉ lấy các lô còn tồn kho
            ->orderBy('import_date', 'asc')
            ->orderBy('expiry_date', 'asc')
            ->orderBy('id', 'asc');

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

    /**
     * POST /api/inventory/deduct - Trừ kho theo nguyên tắc FIFO
     */
    public function deduct(Request $request)
    {
        // Hỗ trợ cả 2 kiểu gửi: 1 món hoặc nhiều món {items: [...]}
        if ($request->has('items')) {
            $validated = $request->validate([
                'items'               => 'required|array|min:1',
                'items.*.fruit_id'    => 'required|integer|min:1',
                'items.*.quantity_kg' => 'required|numeric|min:0.1',
            ]);
            $items = $validated['items'];
        } else {
            $single = $request->validate([
                'fruit_id'    => 'required|integer|min:1',
                'quantity_kg' => 'required|numeric|min:0.1',
            ]);
            $items = [$single];
        }

        try {
            $deductionLogs = DB::connection('inventory_sqlite')->transaction(function () use ($items) {
                $logs = [];

                foreach ($items as $item) {
                    $fruitId = (int) $item['fruit_id'];
                    $neededKg = round((float) $item['quantity_kg'], 2);

                    // Lấy các lô còn hàng theo thứ tự FIFO: nhập trước -> hết hạn trước -> id nhỏ trước
                    $batches = InventoryBatch::where('fruit_id', $fruitId)
                        ->where('quantity_kg', '>', 0)
                        ->orderBy('import_date', 'asc')
                        ->orderBy('expiry_date', 'asc')
                        ->orderBy('id', 'asc')
                        ->lockForUpdate()
                        ->get();

                    $availableKg = round((float) $batches->sum('quantity_kg'), 2);

                    if ($availableKg < $neededKg) {
                        throw new \RuntimeException(
                            "Trái cây ID {$fruitId} không đủ tồn kho (Cần: {$neededKg} kg, Hiện còn: {$availableKg} kg)."
                        );
                    }

                    $remainingToDeduct = $neededKg;
                    $affectedBatches = [];

                    foreach ($batches as $batch) {
                        if ($remainingToDeduct <= 0) {
                            break;
                        }

                        $batchQty = (float) $batch->quantity_kg;
                        $takeKg = min($batchQty, $remainingToDeduct);

                        $batch->quantity_kg = round($batchQty - $takeKg, 2);
                        $batch->save();

                        $remainingToDeduct = round($remainingToDeduct - $takeKg, 2);

                        $affectedBatches[] = [
                            'batch_id'     => $batch->id,
                            'import_date'  => $batch->import_date->format('Y-m-d'),
                            'deducted_kg'  => $takeKg,
                            'remaining_kg' => $batch->quantity_kg,
                        ];
                    }

                    $logs[] = [
                        'fruit_id'         => $fruitId,
                        'total_deducted'   => $neededKg,
                        'affected_batches' => $affectedBatches,
                    ];
                }

                return $logs;
            });

            return response()->json([
                'message' => 'Trừ kho FIFO thành công',
                'details' => $deductionLogs,
            ], 200);
        } catch (\RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}

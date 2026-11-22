<?php

namespace ProductService\Controllers;

use App\Http\Controllers\Controller;
use ProductService\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 1. Lấy danh sách + Tìm kiếm theo tên (?search=...)
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->orderBy('id', 'desc')->get()
        ], 200);
    }

    // 2. Thêm trái cây mới
    public function store(Request $request)
    {
        $validated = $request->validate([
    'name' => 'required|string|max:255',
    'price_per_kg' => 'required|numeric|min:0',
    'origin' => 'nullable|string|max:255',
    'description' => 'nullable|string',
    'image_url' => 'nullable|string', // <-- Thêm dòng này
]);

        $product = Product::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Thêm trái cây thành công',
            'data' => $product
        ], 201);
    }

    // 3. Xem chi tiết 1 trái cây
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy trái cây'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $product], 200);
    }

    // 4. Cập nhật trái cây
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy trái cây'], 404);
        }

        $validated = $request->validate([
    'name' => 'sometimes|required|string|max:255',
    'price_per_kg' => 'sometimes|required|numeric|min:0',
    'origin' => 'nullable|string|max:255',
    'description' => 'nullable|string',
    'image_url' => 'nullable|string', // <-- Thêm dòng này
]);

        $product->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
            'data' => $product
        ], 200);
    }

    // 5. Xóa trái cây
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy trái cây'], 404);
        }

        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa trái cây thành công'
        ], 200);
    }
}
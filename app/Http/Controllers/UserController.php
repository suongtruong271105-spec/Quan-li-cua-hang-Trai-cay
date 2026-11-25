<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->user('api')?->role !== 'admin') {
            return response()->json([
                'message' => 'Bạn không có quyền thực hiện thao tác này.',
            ], 403);
        }

        $users = User::query()
            ->select(['id', 'name', 'email', 'role', 'created_at'])
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'data' => $users,
        ]);
    }

    public function updateRole(Request $request, int $id): JsonResponse
    {
        if ($request->user('api')?->role !== 'admin') {
            return response()->json([
                'message' => 'Bạn không có quyền thực hiện thao tác này.',
            ], 403);
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in(['customer', 'inventory_manager', 'admin'])],
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'role' => $validated['role'],
        ]);

        return response()->json([
            'message' => 'Cập nhật quyền thành công.',
            'user' => $user,
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        if ($request->user('api')?->role !== 'admin') {
            return response()->json([
                'message' => 'Bạn không có quyền thực hiện thao tác này.',
            ], 403);
        }

        if ($request->user('api')?->id === $id) {
            return response()->json([
                'message' => 'Không thể tự xóa tài khoản admin đang đăng nhập.',
            ], 422);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'Xóa người dùng thành công.',
        ]);
    }
}

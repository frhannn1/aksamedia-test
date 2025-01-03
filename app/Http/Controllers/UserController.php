<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getDataUser(Request $request){
    // Ambil pengguna yang terautentikasi
    $user = Auth::guard('sanctum')->user();

    if ($user) {
        $userData = User::find($user->id);

        return response()->json([
            'success' => true,
            'user' => $userData
        ]);
    }
     // Jika pengguna tidak ditemukan
    return response()->json([
        'success' => false,
        'message' => 'User not found or not authenticated.'
    ], 401);
    }

    public function updateName(Request $request, $id){
        try{
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
            ]);


            $employee = DB::table('users')->where('id', $id)->first();

            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'user tidak ditemukan',
                ], 404);
            }


            // Tambahkan timestamps untuk updated_at
            $validated['updated_at'] = now();
            DB::table('users')->where('id', $id)->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'nama user diperbaharui',
            ], 200); // Status 200: OK
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani kesalahan validasi
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422); // Status 422: Unprocessable Entity
        } catch (\Exception $e) {
            // Tangani kesalahan lain
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);

        }

    }
}

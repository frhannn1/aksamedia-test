<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
{
    public function getAllDataDivisi(Request $request){
        try {
            // Ambil parameter filter nama jika ada
            $name = $request->input('name');

            // Query untuk mendapatkan data divisi

            $query = DB::table('divisions')->select('id', 'name');
            // Tambahkan filter nama jika parameter tersedia
            if (!empty($name)) {
                $query->where('name', 'LIKE', '%' . $name . '%');
            }

            // Paginate data dengan 6 item per halaman
            $divisions = $query->paginate(6);

            if ($divisions->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data divisi tidak ditemukan',
                    'data' => null,
                ], 404); // Mengembalikan status 404 jika data tidak ditemukan
            }

            // Format response
            return response()->json([
                'status' => 'success',
                'message' => 'Data divisions berhasil diambil',
                'data' => [
                    'divisions' => $divisions->items(),
                ],
                'pagination' => [
                    'current_page' => $divisions->currentPage(),
                    'total_items' => $divisions->total(),
                    'per_page' => $divisions->perPage(),
                    'last_page' => $divisions->lastPage(),
                    'next_page_url' => $divisions->nextPageUrl(),
                    'previous_page_url' => $divisions->previousPageUrl(),
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle error jika ada kesalahan pada server atau query
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}

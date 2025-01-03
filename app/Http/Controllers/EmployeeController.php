<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class EmployeeController extends Controller
{
    public function getAllDataEmployee(Request $request)
    {
        try {
            // Ambil parameter filter nama dan division_id jika ada
            $name = $request->input('name');
            $divisionId = $request->input('division_id');

            // Query untuk mendapatkan data employee
            $query = DB::table('employees')
                ->join('divisions', 'employees.division_id', '=', 'divisions.id')
                ->select(
                    'employees.id',
                    'employees.image',
                    'employees.name',
                    'employees.phone',
                    'employees.position',
                    'divisions.id as division_id',
                    'divisions.name as division_name'
                );

            // Tambahkan filter
            if (!empty($name)) {
                $query->where('employees.name', 'LIKE', '%' . $name . '%');
            }
            if (!empty($divisionId)) {
                $query->where('employees.division_id', $divisionId);
            }


            $employees = $query->paginate(50);

            // Mengembalikan status 404 jika data tidak ditemukan
            if ($employees->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data divisi tidak ditemukan',
                    'data' => null,
                ], 404);
            }

            $mappedEmployees = $employees->items();
            foreach ($mappedEmployees as $employee) {
                $employee->division = [
                    'id' => $employee->division_id,
                    'name' => $employee->division_name,
                ];
                unset($employee->division_id, $employee->division_name);
            }

            // Format response
            return response()->json([
                'status' => 'success',
                'message' => 'Data employees berhasil diambil',
                'data' => [
                    'employees' => $mappedEmployees,
                ],
                'pagination' => [
                    'current_page' => $employees->currentPage(),
                    'total_items' => $employees->total(),
                    'per_page' => $employees->perPage(),
                ],
            ], 200);
            // Handle error
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function store(Request $request)
    {

        try {
            $validate = $request->validate([

                'name' => 'required',
                'division_id' => 'required',
                'position' => 'required',
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $validated['image'] = $imagePath; // Tambahkan path gambar ke dalam data validasi
            } else {
                $imagePath = null;
                $validated['image'] = null; // Tetapkan null jika tidak ada gambar
            }

            // Tambahkan data ke database
            DB::table('employees')->insert([
                'id' => Str::uuid(),
                'image' => $imagePath, // Path gambar yang telah disimpan
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'division_id' => $request->input('division_id'),
                'position' => $request->input('position'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Berikan respons sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Data pegawai berhasil ditambahkan',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500); // Status 500: Internal Server Error
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar opsional
                'name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:15',
                'division_id' => 'nullable|exists:divisions,id', // Validasi jika divisi diupdate
                'position' => 'nullable|string|max:255',
            ]);


            $employee = DB::table('employees')->where('id', $id)->first();

            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pegawai tidak ditemukan',
                ], 404);
            }
            // Simpan file gambar baru jika ada
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $validated['image'] = $imagePath; // Tambahkan path gambar ke data validasi
            }

            // Tambahkan timestamps untuk updated_at
            $validated['updated_at'] = now();
            DB::table('employees')->where('id', $id)->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Data pegawai berhasil diperbarui',
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


    public function delete($id)
    {
        try {
            // Cari data pegawai berdasarkan ID
            $employee = Employee::find($id);

            // Jika pegawai tidak ditemukan
            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pegawai tidak ditemukan',
                ], 404); // Status 404: Not Found
            }

            // Hapus gambar jika ada di dalam penyimpanan
            if ($employee->image) {
                // Menghapus file gambar dari storage
                Storage::disk('public')->delete($employee->image);
            }

            // Hapus data pegawai
            $employee->delete();

            // Respons sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Data pegawai berhasil dihapus',
            ], 200); // Status 200: OK
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi masalah pada server
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500); // Status 500: Internal Server Error
        }
    }
}

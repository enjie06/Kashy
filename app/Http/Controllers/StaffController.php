<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Ambil semua staff (kasir & karyawan), bukan owner.
     */
    public function index()
    {
        $staff = User::whereIn('role', ['kasir', 'karyawan'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'email'      => $u->email,
                'role'       => ucfirst($u->role),
                'status'     => $u->is_active ? 'Aktif' : 'Nonaktif',
                'created_at' => $u->created_at->translatedFormat('d M Y'),
            ]);

        return response()->json($staff);
    }

    /**
     * Buat akun staff baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:kasir,karyawan',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role'      => strtolower($validated['role']),
            'password'  => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Staff {$user->name} berhasil ditambahkan.",
            'staff'   => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'role'   => ucfirst($user->role),
                'status' => 'Aktif',
            ],
        ]);
    }

    /**
     * Toggle status aktif/nonaktif staff.
     */
    public function toggleStatus(User $user)
    {
        // Pastikan bukan owner yang diubah
        if ($user->role === 'owner') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengubah status akun owner.',
            ], 403);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return response()->json([
            'success' => true,
            'message' => "Akun {$user->name} berhasil {$statusText}.",
            'is_active' => $user->is_active,
        ]);
    }

    /**
     * Hapus akun staff (soft delete via nonaktifkan, atau hard delete).
     */
    public function destroy(User $user)
    {
        if ($user->role === 'owner') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun owner.',
            ], 403);
        }

        $name = $user->name;
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => "Akun {$name} berhasil dihapus.",
        ]);
    }
}
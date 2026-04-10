<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function idex()
    {
        $user = auth()->user();

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ], 200);
    }

    public function showCustomers()
    {
        $users = User::oldest()->where('role', 'customer');

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ], 200);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully!',
            'data' => $user,
        ], 200);
    }

    public function updateIsBanned(Request $request, $id)
    {
        $request->validate([
            'is_banned' => 'required|boolean',
        ]);

        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Security: Admin cannot be banned
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'You cannot ban yourself!'], 400);
        }

        $user->update([
            'is_banned' => $request->is_banned,
        ]);

        $statusMessage = $request->is_banned ? 'User has been banned.' : 'User has been unbanned.';

        return response()->json([
            'status' => 'success',
            'message' => $statusMessage,
            'data' => $user,
        ], 200);
    }
}

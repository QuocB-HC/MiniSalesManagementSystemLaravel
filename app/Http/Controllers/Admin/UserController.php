<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::oldest()->where('role', 'customer')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function updateIsBanned(Request $request, User $user)
    {
        $request->validate([
            'is_banned' => 'required|boolean',
        ]);

        $user->update([
            'is_banned' => $request->is_banned
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User banned status updated successfully!');
    }
}

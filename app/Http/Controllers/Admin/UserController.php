<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        $newCount = User::where('created_at', '>=', now()->subDay())->count();

        return view('admin.users.index', compact('users', 'newCount'));
    }

    public function destroy(User $user)
    {
        $current = auth()->user();

        if ($current->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Vous ne pouvez pas vous supprimer.');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Impossible de supprimer un administrateur.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'Utilisateur supprimé avec succès.');
    }

 
    public function promote(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Cet utilisateur est déjà administrateur.');
        }

        $user->update([
            'is_admin' => true,
            'role' => User::ROLE_ADMIN,
        ]);

        return redirect()->route('admin.users.index')->with('status', 'Utilisateur promu administrateur.');
    }

 
    public function demote(User $user)
    {
        $current = auth()->user();

        if ($current->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Vous ne pouvez pas vous rétrograder.');
        }

        if (! $user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Cet utilisateur n\'est pas administrateur.');
        }

        $user->update([
            'is_admin' => false,
            'role' => User::ROLE_COUNSELOR,
        ]);

        return redirect()->route('admin.users.index')->with('status', 'Utilisateur rétrogradé.');
    }
}

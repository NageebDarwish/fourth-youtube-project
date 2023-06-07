<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersContoller extends Controller
{
    public function GetUsers()
    {
        return User::all();
    }
    // Get Auth User
    public function authUser()
    {
        return Auth::user();
    }

    // Get Specific User
    public function getUser($id)
    {
        return User::findOrFail($id);
    }

    // Edit User
    public function editUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
    }

    // Delete User
    public function destroy($id)
    {
        return  User::findOrFail($id)->delete();
    }
}

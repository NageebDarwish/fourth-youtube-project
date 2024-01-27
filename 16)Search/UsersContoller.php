<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersContoller extends Controller
{
    public function GetUsers(Request $request)
    {
        $users = User::paginate($request->input('limit', 10));
        return $users;
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

    // Add User

    public function addUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);
        $user =  DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'user' => $user,
        ], 200);
    }

    // Edit User
    public function editUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
        ]);
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();
    }

    // Search On Users
    public function search(Request $request)
     {
            $query = $request->input('title');
            $results = User::where('name', 'like', "%$query%")->get();
            return response()->json($results);
     }

    // Delete User
    public function destroy($id)
    {
        return  User::findOrFail($id)->delete();
    }
}

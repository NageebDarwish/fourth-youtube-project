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
}

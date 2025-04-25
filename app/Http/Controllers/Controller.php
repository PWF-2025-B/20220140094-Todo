<?php

namespace App\Http\Controllers;

use App\Models\User;

abstract class Controller
{
    public function index()
    {
        $user = User::where('id', '!=', 1) ->orderBy('name') ->paginate(10);
        return view('user.index', compact('users'));
    }
}

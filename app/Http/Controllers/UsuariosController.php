<?php

namespace App\Http\Controllers;

use App\Models\HomeworkManage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuariosController extends Controller
{
    public function index()
    {
        return view('usuarios');
    }

    public function getByRole(Request $request)
    {
        $excludes_id = HomeworkManage::select('user_id')
        ->where('admin_id', Auth::user()->id)
        ->get()
        ->pluck('user_id')
        ->toArray();
        array_push($excludes_id, Auth::user()->id);
        $users = User::select('id', 'name')->role($request->role)->whereNotIn('id', $excludes_id)->get();
        return response()->json($users);
    }
}

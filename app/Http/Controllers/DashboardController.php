<?php

namespace App\Http\Controllers;

use App\Models\JefeHuertoProfile;
use App\Models\Priority;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $users = JefeHuertoProfile::select('user_id')->with('jefe:id,name')->where('admin_id', Auth::user()->id)->get();
        $priorities = Priority::all();
        if(Auth::user()->hasRole('Gerente')){
            $administrativos = DB::table('admin_users')
            ->select('users.id', 'users.name')
            ->join('users', 'users.id', 'admin_users.user_id')
            ->where('admin_id', Auth::user()->id)
            ->get();
            $administrativos_id = DB::table('admin_users')
            ->select('users.id')
            ->join('users', 'users.id', 'admin_users.user_id')
            ->where('admin_id', Auth::user()->id)
            ->pluck('id');
            $administradores = DB::table('admin_users')
            ->select('users.id', 'users.name')
            ->join('users', 'users.id', 'admin_users.user_id')
            ->where('admin_id', $administrativos_id)
            ->get();
        }elseif(Auth::user()->hasRole('Administrativo')){
            $administrativos    = [];
            $administradores    = DB::table('admin_users')
            ->select('users.id', 'users.name')
            ->join('users', 'users.id', 'admin_users.user_id')
            ->where('admin_id', Auth::user()->id)
            ->get();
        }else{
            $administrativos    = [];
            $administradores    = [];
        }
        return view('dashboard', compact('users', 'priorities', 'administrativos', 'administradores'));
    }
}

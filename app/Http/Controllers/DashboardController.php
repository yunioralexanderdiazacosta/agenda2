<?php

namespace App\Http\Controllers;

use App\Models\JefeHuertoProfile;
use App\Models\Priority;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $users = JefeHuertoProfile::select('user_id')->with('jefe:id,name')->where('admin_id', Auth::user()->id)->get();
        $priorities = Priority::all();
        if(Auth::user()->hasRole('Administrativo')){
            $gerentes           = User::select('id', 'name')->role('Gerente')->get();
            $administradores    = [];
        }elseif(Auth::user()->hasRole('Gerente')){
            $gerentes           = [];
            $administradores    = DB::table('admin_users')
            ->select('users.id', 'users.name')
            ->join('users', 'users.id', 'admin_users.user_id')
            ->where('admin_id', Auth::user()->id)
            ->get();
    
        }
        else{
            $gerentes           = [];
            $administradores    = [];
        }
        return view('dashboard', compact('users', 'priorities', 'gerentes', 'administradores'));
    }
}

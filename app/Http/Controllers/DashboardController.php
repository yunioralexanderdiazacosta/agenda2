<?php

namespace App\Http\Controllers;

use App\Models\JefeHuertoProfile;
use App\Models\Priority;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $users = JefeHuertoProfile::select('user_id')->with('jefe:id,name')->where('admin_id', Auth::user()->id)->get();
        $priorities = Priority::all();
        if(Auth::user()->hasRole('Gerente')){
            $administradores = User::select('id', 'name')->role('Admin')->get();
        }else{
            $administradores = [];
        }
        return view('dashboard', compact('users', 'priorities', 'administradores'));
    }
}

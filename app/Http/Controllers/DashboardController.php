<?php

namespace App\Http\Controllers;

use App\Models\JefeHuertoProfile;
use App\Models\Priority;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $users = JefeHuertoProfile::select('user_id')->with('jefe:id,name')->where('admin_id', Auth::user()->id)->get();
        $priorities = Priority::all();
        return view('dashboard', compact('users', 'priorities'));
    }
}

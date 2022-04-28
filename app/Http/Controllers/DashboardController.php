<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke($id = '', $date = '', $view = '')
    {
        $priorities = Priority::all();
        $teams = DB::table('users as u')
        ->select('u.id', 'u.name')
        ->join('homework_views as hv', 'hv.user_id', 'u.id')
        ->where('hv.admin_id', Auth::user()->id)
        ->get();
        return view('dashboard', compact('priorities', 'teams', 'id', 'date', 'view'));
    }
}

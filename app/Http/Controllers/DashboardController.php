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
        $priorities = Priority::all();
        return view('dashboard', compact('priorities'));
    }
}

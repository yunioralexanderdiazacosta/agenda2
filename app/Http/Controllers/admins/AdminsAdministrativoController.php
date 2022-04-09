<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminsAdministrativoController extends Controller
{
    public function __invoke(int $id)
    {
        $administradores = DB::table('admin_users')
        ->select('users.id', 'users.name')
        ->join('users', 'users.id', 'admin_users.user_id')
        ->where('admin_id', $id)
        ->get();

        return response()->json($administradores);
    }
}

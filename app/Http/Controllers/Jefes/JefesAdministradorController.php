<?php

namespace App\Http\Controllers\Jefes;

use App\Http\Controllers\Controller;
use App\Models\JefeHuertoProfile;

class JefesAdministradorController extends Controller
{
    public function __invoke(int $id)
    {
        $jefes = JefeHuertoProfile::with('jefe')->where('admin_id', $id)->get();
        return response()->json($jefes);
    }
}

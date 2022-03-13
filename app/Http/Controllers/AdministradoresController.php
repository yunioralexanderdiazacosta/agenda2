<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministradoresController extends Controller
{
    public function __invoke()
    {
        return view('administradores');
    }
}

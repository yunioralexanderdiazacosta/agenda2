<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GerentesController extends Controller
{
    public function __invoke()
    {
        return view('gerentes');
    }
}

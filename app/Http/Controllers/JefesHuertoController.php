<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JefesHuertoController extends Controller
{
    public function __invoke()
    {
        return view('jefes-huerto');
    }
}

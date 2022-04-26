<?php

namespace App\Http\Controllers\Homeworks;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\Homework;
use App\Models\JefeHuertoProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditHomeworkController extends Controller
{
    public function __invoke(int $id)
    {
        $homework = Homework::find($id);
        $data['homework'] = $homework;
        return response()->json($data);
    }
}

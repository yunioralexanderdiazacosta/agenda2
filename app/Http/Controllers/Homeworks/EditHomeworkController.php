<?php

namespace App\Http\Controllers\Homeworks;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\JefeHuertoProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditHomeworkController extends Controller
{
    public function __invoke(int $id)
    {
        $homework = Homework::find($id);

        if($homework->for_admin == 0 && Auth::user()->hasRole('Gerente')){
            $jh = JefeHuertoProfile::select('admin_id')->where('user_id', $homework->user_id)->first();
        }
        $data = [
            'homework' => $homework,
            'admin_id' => empty($jh) ? '' : $jh->admin_id
        ];
        return response()->json($data);
    }
}

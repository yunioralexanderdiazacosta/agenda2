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
        if($homework->for_admin == 2){
            $data['gerente_id'] = AdminUser::select('admin_id')->where('user_id', $homework->user_id)->first()->admin_id;
        }elseif($homework->for_admin == 3){
            $data['admin_id']   = JefeHuertoProfile::select('admin_id')->where('user_id', $homework->user_id)->first()->admin_id;
            $data['gerente_id'] = AdminUser::select('admin_id')->where('user_id', $data['admin_id'])->first()->admin_id;
        }
        return response()->json($data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use App\Models\JefeHuertoProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeworksController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $start = date('Y-m-d', strtotime($request->start));
        $end = date('Y-m-d', strtotime($request->end));
        if($user->hasrole('Gerente')){
            $ids = $this->get_users();
            $homeworks = DB::table('homework as h')
            ->select('h.id', 'h.title', 'h.date', 'p.color', 'h.for_admin', 'h.status')
            ->join('priorities as p', 'p.id', 'h.priority_id')
            ->whereIn('h.user_id', $ids)
            ->whereDate('h.date', '>=', $start)
            ->whereDate('h.date',   '<=', $end)
            ->get()
            ->transform(function($homework){
                $data = [
                    'id'    => $homework->id,
                    'title' => $homework->title,
                    'start' => $homework->date,
                    'color' => $homework->color
                ];
                if($homework->status == 1){
                    $data['icon'] = "check";
                }
                return $data;
            });
        }else if($user->hasrole('Administrativo')){

            $ids = $this->get_users(false);
            $homeworks = DB::table('homework as h')
            ->select('h.id', 'h.title', 'h.date', 'p.color', 'h.for_admin', 'h.status')
            ->join('priorities as p', 'p.id', 'h.priority_id')
            ->whereIn('h.user_id', $ids)
            ->whereDate('h.date', '>=', $start)
            ->whereDate('h.date',   '<=', $end)
            ->get()
            ->transform(function($homework){
                $data = [
                    'id' => $homework->id,
                    'title' => $homework->title,
                    'start' => $homework->date,
                    'color' => $homework->color
                ];
                if($homework->for_admin == 1){
                    $data['startEditable'] = false;
                }
                if($homework->status == 1){
                    $data['icon'] = "check";
                }
                return $data;
            });
        }else if($user->hasrole('Admin')){
            $jh = JefeHuertoProfile::select('user_id')->where('admin_id', Auth::user()->id)->get()->pluck('user_id');

            $homeworks = DB::table('users')
            ->select('homework.id', 'homework.title', 'homework.date', 'priorities.color', 'homework.for_admin', 'homework.status')
            ->join('homework', 'users.id', 'homework.user_id')
            ->join('priorities', 'homework.priority_id', 'priorities.id')
            ->whereIn('user_id',  [Auth::user()->id])
            ->orWhereIn('user_id', $jh)
            ->whereDate('homework.date', '>=', $start)
            ->whereDate('homework.date',   '<=', $end)
            ->get()
            ->transform(function($homework){
                $data = [
                    'id' => $homework->id,
                    'title' => $homework->title,
                    'start' => $homework->date,
                    'color' => $homework->color
                ];
                if($homework->for_admin == 2){
                    $data['startEditable'] = false;
                }
                if($homework->status == 1){
                    $data['icon'] = "check";
                }
                return $data;
            });
        }else if($user->hasrole('JH')){
            $homeworks = DB::table('users')
            ->select('homework.id', 'homework.title', 'homework.date', 'priorities.color', 'homework.status', 'homework.for_admin')
            ->join('jefe_huerto_profiles', 'users.id', '=', 'jefe_huerto_profiles.user_id')
            ->join('homework', 'users.id', 'homework.user_id')
            ->join('priorities', 'homework.priority_id', 'priorities.id')
            ->where('users.id', $user->id)
            ->whereDate('homework.date', '>=', $start)
            ->whereDate('homework.date',   '<=', $end)
            ->get()
            ->transform(function($homework){
                $data = [
                    'id' => $homework->id,
                    'title' => $homework->title,
                    'start' => $homework->date,
                    'color' => $homework->color
                ];
                if($homework->status == 1){
                    $data['icon'] = "check";
                }
                if($homework->for_admin == 3){
                    $data['startEditable'] = false;
                }
                return $data;
            });
        }
        return response()->json($homeworks);
    }

    private function get_users($is_administrativo = true)
    {
        if($is_administrativo == true){
            $administrativos_id = DB::table('admin_users')
            ->select('users.id')
            ->join('users', 'users.id', 'admin_users.user_id')
            ->where('admin_id', Auth::user()->id)->get()->pluck('id')->toArray();
        }else{
            $administrativos_id = [Auth::user()->id];
        }

        $administradores_id = DB::table('admin_users')
        ->select('a.id')
        ->join('users as a', 'a.id', 'admin_users.user_id')
        ->join('users as g', 'g.id', 'admin_users.admin_id')
        ->join('fields', 'fields.id', 'a.field_id')
        ->whereIn('admin_id', $administrativos_id)->get()->pluck('id')->toArray();

        $jefes_id =  DB::table('jefe_huerto_profiles as jh')
        ->select('u.id')
        ->join('users as u', 'u.id', 'jh.user_id')
        ->join('admin_users as admin', 'admin.user_id', 'jh.admin_id')
        ->whereIn('admin.admin_id', $administrativos_id)
        ->get()->pluck('id')->toArray();
        $ids = array_merge($administrativos_id, $administradores_id, $jefes_id);
        return $ids;
    }
}

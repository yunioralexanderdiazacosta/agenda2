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

        if($user->hasrole('Admin')){
            $jh = JefeHuertoProfile::select('user_id')->where('admin_id', Auth::user()->id)->get()->pluck('user_id');

            $homeworks = DB::table('users')
            ->select('homework.id', 'homework.title', 'homework.date', 'priorities.color', 'homework.for_admin')
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
                    'color' => $homework->for_admin == 1 ?  '#1565c0' : $homework->color
                ];
                if($homework->for_admin == 1){
                    $data['startEditable'] = false;
                }
                return $data;
            });
        }else if($user->hasrole('JH')){
            $homeworks = DB::table('users')
            ->select('homework.id', 'homework.title', 'homework.date', 'priorities.color')
            ->join('jefe_huerto_profiles', 'users.id', '=', 'jefe_huerto_profiles.user_id')
            ->join('homework', 'users.id', 'homework.user_id')
            ->join('priorities', 'homework.priority_id', 'priorities.id')
            ->where('users.id', $user->id)
            ->whereDate('homework.date', '>=', $start)
            ->whereDate('homework.date',   '<=', $end)
            ->get()
            ->transform(function($homework){
                return [
                    'id' => $homework->id,
                    'title' => $homework->title,
                    'start' => $homework->date,
                    'color' => $homework->color
                ];
            });
        }else if($user->hasrole('Gerente')){
            $homeworks = DB::table('users')
            ->select('homework.id', 'homework.title', 'homework.date', 'priorities.color', 'homework.for_admin')
            ->join('homework', 'users.id', 'homework.user_id')
            ->join('priorities', 'homework.priority_id', 'priorities.id')
            ->whereDate('homework.date', '>=', $start)
            ->whereDate('homework.date',   '<=', $end)
            ->get()
            ->transform(function($homework){
                return [
                    'id' => $homework->id,
                    'title' => $homework->title,
                    'start' => $homework->date,
                    'color' => $homework->for_admin ? '#1565cz0' : $homework->color
                ];
            });
        }
        return response()->json($homeworks);
    }
}

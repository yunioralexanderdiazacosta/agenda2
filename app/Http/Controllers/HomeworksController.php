<?php

namespace App\Http\Controllers;

use App\Models\Homework;
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
            $homeworks = DB::table('users')
            ->select('homework.id', 'homework.title', 'homework.date', 'priorities.color')
            ->join('jefe_huerto_profiles', 'users.id', '=', 'jefe_huerto_profiles.user_id')
            ->join('homework', 'users.id', 'homework.user_id')
            ->join('priorities', 'homework.priority_id', 'priorities.id')
            ->where('admin_id', $user->id)
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
        }
        return response()->json($homeworks);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use App\Models\HomeworkView;
use App\Models\JefeHuertoProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeworksController extends Controller
{
    public function __invoke($id='', Request $request)
    {
        $start          = date('Y-m-d', strtotime($request->start));
        $end            = date('Y-m-d', strtotime($request->end));
        if($id == '' || $id == 0){
            $ids        = $this->get_users();
        }else{
            $ids        = [$id];
        }
        $homeworks = DB::table('homework as h')
            ->select('h.id', 'h.title', 'h.date', 'p.color', 'h.for_admin', 'h.status', 'u.name')
            ->join('priorities as p', 'p.id', 'h.priority_id')
            ->join('users as u', 'u.id', 'h.user_id')
            ->whereIn('h.user_id', $ids)
            ->whereDate('h.date', '>=', $start)
            ->whereDate('h.date',   '<=', $end)
            ->get()
            ->transform(function($homework){
                $data = [
                    'id'    => $homework->id,
                    'title' => $this->getInitialName($homework->name) . ' - ' . $homework->title,
                    'start' => $homework->date,
                    'color' => $homework->color,
                ];
                if($homework->status == 1){
                    $data['icon'] = "check";
                }
                return $data;
            });
        return response()->json($homeworks);
    }

    private function get_users()
    {
        $users_id = HomeworkView::select('user_id')->where('admin_id', Auth::user()->id)->get()->pluck('user_id')->toArray();
        array_push($users_id, Auth::user()->id);
        return $users_id;
    }

    private function getInitialName($name)
    {
        $initials = '';
        $explode = explode(" ", $name);
        foreach($explode as $x){
            $initials .=  $x[0];
        }
        return strtoupper($initials);
    }
}

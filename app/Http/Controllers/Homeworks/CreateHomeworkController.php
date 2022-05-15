<?php

namespace App\Http\Controllers\Homeworks;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormHomeworkRequest;
use App\Mail\SendHomeworkNotificationEmail;
use App\Models\Homework;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CreateHomeworkController extends Controller
{
    public function __invoke(FormHomeworkRequest $request)
    {
        $homework               = New Homework();
        $homework->date         = $request->date;
        $homework->title        = $request->title;
        $homework->description  = $request->description;
        $homework->user_id      = $request->for_admin == 0 ? Auth::user()->id : $request->user_id;
        $homework->priority_id  = $request->priority_id;
        $homework->for_admin    = $request->for_admin;
        $homework->is_own       = $request->for_admin == 0 ? 1 : 0;
        $homework->admin_id     = Auth::user()->id;
        $homework->save();
        if($request->send_notification == 1){
            $user = User::select('email')->where('id', $homework->user_id)->first();
            Mail::to($user->email)->send(new SendHomeworkNotificationEmail($request->title));
        }

        return response()->json([
            'success' => true
        ]);
    }
}

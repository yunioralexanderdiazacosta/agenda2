<?php

namespace App\Http\Controllers\Homeworks;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormHomeworkRequest;
use App\Models\Homework;
use Illuminate\Http\Request;

class UpdateHomeworkController extends Controller
{
    public function __invoke(int $id, FormHomeworkRequest $request)
    {
        $homework = Homework::find($id);
        $homework->date = $request->date;
        $homework->title = $request->title;
        $homework->description = $request->description;
        $homework->user_id = $request->user_id;
        $homework->priority_id = $request->priority_id;
        $homework->for_admin = $request->for_admin == 'false' ? 0 : 1;
        $homework->save();
        return response()->json([
            'success' => true
        ]);
    }
}

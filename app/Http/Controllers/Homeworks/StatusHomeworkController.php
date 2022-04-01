<?php

namespace App\Http\Controllers\Homeworks;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use Illuminate\Http\Request;

class StatusHomeworkController extends Controller
{
    public function __invoke(int $id, Request $request)
    {
        $homework = Homework::find($id);
        $homework->status   = $request->status;
        $homework->comment  = $request->comment;
        $homework->save();
        return response()->json([
            'success' => true
        ]);
    }
}

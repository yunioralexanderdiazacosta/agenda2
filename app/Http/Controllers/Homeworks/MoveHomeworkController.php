<?php

namespace App\Http\Controllers\Homeworks;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use Illuminate\Http\Request;

class MoveHomeworkController extends Controller
{
    public function __invoke(int $id, Request $request)
    {
        $homework = Homework::find($id);
        $homework->date = $request->min;
        $homework->save();

        return response()->json([
            'success' => true
        ]);
    }
}

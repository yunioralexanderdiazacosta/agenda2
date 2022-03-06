<?php

namespace App\Http\Controllers\Homeworks;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use Illuminate\Http\Request;

class DeleteHomeworkController extends Controller
{
    public function __invoke(int $id)
    {
        $homework = Homework::find($id);
        if($homework) $homework->delete();
        return response()->json([
            'success' => true
        ]);
    }
}

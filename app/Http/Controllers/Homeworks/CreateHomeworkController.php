<?php

namespace App\Http\Controllers\Homeworks;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Tareas\FormTareaComponent;
use App\Http\Requests\FormHomeworkRequest;
use App\Models\Homework;

class CreateHomeworkController extends Controller
{
    public function __invoke(FormHomeworkRequest $request)
    {
        $homework = New Homework();
        $homework->date = $request->date;
        $homework->title = $request->title;
        $homework->description = $request->description;
        $homework->user_id = $request->user_id;
        $homework->priority_id = $request->priority_id;
        $homework->save();
        return response()->json([
            'success' => true
        ]);

    }
}

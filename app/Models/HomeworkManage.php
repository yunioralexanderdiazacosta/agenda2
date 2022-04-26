<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkManage extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id', 'user_id'];
}

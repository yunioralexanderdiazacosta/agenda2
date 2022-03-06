<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'title', 'description', 'user_id', 'priority_id'];
    protected $hidden = ['created_at', 'updated_at'];
}

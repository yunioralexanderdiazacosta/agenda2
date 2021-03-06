<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'title', 'description', 'user_id', 'priority_id', 'for_admin', 'admin_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}

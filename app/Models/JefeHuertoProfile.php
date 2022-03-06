<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JefeHuertoProfile extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'field_id', 'admin_id'];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function jefe()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

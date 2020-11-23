<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'type',
        'uploaded_by_admin',
        'admin_name'
    ];

    protected $casts = [
        'uploaded_by_admin' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordHistory extends Model
{
    protected $table = 'password_history';

    protected $fillable = ['password'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

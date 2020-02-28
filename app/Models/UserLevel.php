<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    protected $table = 'user_level';

    protected $fillable = [
        'name',
        'mark',
        'order',
        'remark'
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

}

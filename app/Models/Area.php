<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'type',
        'name',
        'order'
    ];

    public function parent()
    {
        return $this->belongsTo(Area::class);
    }

    public function getFullNameAttribute()
    {
        if ($this->type == 1) {
            return $this->name;
        }

        return $this->parent->full_name . $this->name;
    }

    public function children()
    {
        return $this->hasMany(Area::class, 'parent_id');
    }

    public function brothers()
    {
        if ($this->type == 1) {
            return $this->children();
        }

        return $this->parent->children();
    }

}

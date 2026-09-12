<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    protected $guarded = ['id'];

    public function applications()
    {
        return $this->hasMany(PklApplication::class);
    }
}

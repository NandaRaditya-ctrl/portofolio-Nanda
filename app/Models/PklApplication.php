<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PklApplication extends Model
{
    protected $guarded = ['id'];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

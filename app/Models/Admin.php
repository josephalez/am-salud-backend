<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;


class Admin extends User
{
    //
    protected static function boot(){
    	parent::boot();
    	static::addGlobalScope('worker', function (Builder $builder) {
            $builder->where('role', '=', User::Role[0]);
        });
    }
}

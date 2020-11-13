<?php

namespace App\Models;

use App\User;
use App\Service;
use App\Models\WorkerPrice;

use Illuminate\Database\Eloquent\Builder;

class Worker extends User
{
    //
    protected static function boot(){
    	parent::boot();
    	static::addGlobalScope('worker', function (Builder $builder) {
            $builder->where('role', '=', User::Role[2]);
        });
    }
    
    public function getAreaAttribute($value){
    	$val=Service::find($value);
        return $val->name;
    }

    public function prices(){
    	return $this->hasMany(WorkerPrice::class,'worker','id');
    }

    public function service(){
        return $this->belongsTo(Service::class,'area','id');
    }
}

<?php

namespace App\Models;

use App\User;
use App\Price;
use App\Reservation;
use App\Models\Nota;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Client extends User
{
    //
    protected static function boot(){
    	parent::boot();
    	static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('role', '=', User::Role[1]);
        });
    }

    public function presios(){
    	return $this->hasMany(Price::class,'user','id');
    }

    public function notas(){
    	return $this->hasMany(Nota::class,'client','id');
    }

    public function reservations(){
        return $this->hasMany(Reservation::class, 'user', 'id')
        ->join('users', 'users.id', '=', 'reservations.worker')
        ->select('reservations.*', DB::raw('CONCAT(users.name, " ", users.last_name) AS full_name'),
        'users.profile_picture');
    }

    public function balances(){
        return $this->hasMany(BalanceCredito::class,'user_id','id');
    }

    public function stripe(){
        return $this->hasOne(StripeClient::class,"user_id","id");
    }

    
}

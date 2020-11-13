<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TypePayments extends Model
{
    //
    protected $table='type_payments';
    protected $fillable=['name','description'];

    public function users(){
    	return $this->belongsToMany(User::class,'payments_user','payment_id','user_id');
    }
}

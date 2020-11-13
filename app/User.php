<?php

namespace App;

use Faker\Factory;
use App\Models\Nota;
use App\Models\Product;
use App\Models\Asociado;
use App\Models\WorkerPrice;
use App\Models\StripeClient;
use App\Models\TypePayments;
use App\Traits\ConektaTrait;
use App\Models\ConektaClient;
use Laravel\Passport\HasApiTokens;
use App\Jobs\Users\EmailBienvendios;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable 
{
    protected static function booted()
    {
        static::created(function ($user) {
            EmailBienvendios::dispatchNow($user);
        });
    }

    static  function firebase(){
        
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $faker = Factory::create();
            $user->topic_firebase = $faker->unique()->regexify('[a-zA-Z0-9]{50}');
        });
    }
    use HasApiTokens, Notifiable, SoftDeletes,ConektaTrait;
    protected $table='users';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const Role=["admin","user","worker","atencion"];
     
    protected $fillable = [
        "username",
        "name",
        "last_name",
        "phone",
        "email",
        "instagram",
        "description",
        "birthday",
        "address",
        "gender",
        "password",
        "profile_picture",
        "topic_firebase",
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $guarded = [
        "role",
        "area",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        "birthday"=> 'date'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function type_payments(){
        return $this->belongsToMany(TypePayments::class,'payments_user','user_id','payment_id');
    }

    public function Notasworker(){
        return $this->hasMany(Nota::class,'client','id');
    }
    public function asociados(){
        return $this->hasMany(Asociado::class,'user_id','id');
    }


    public function cars(){
        return $this->belongsToMany(Product::class,'cars','user_id','product_id')->withPivot('cantidad');
    }

    public function stripeid(){
        return $this->hasOne(StripeClient::class,"user_id","id");
    }

    public function conekta(){
        return $this->hasOne(ConektaClient::class,'user_id',"id");
    }
    public function authprices(){
        return $this->hasMany(WorkerPrice::class,'worker','id');
    }

    public function reservations(){
        return $this->hasMany(Reservation::class,"user","id");
    }

    public function getProfilePictureAttribute($value)
    {
        return ($value) ? secure_asset(Storage::url($value)) : null;
    }

    protected $attributes = [
        'role' => 'user',
    ];
}

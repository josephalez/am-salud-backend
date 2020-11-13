<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    protected $table="relationships";

    protected $fillable=[
        "user_id",
        "user_related"
    ];
}

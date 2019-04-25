<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'refresh_token',
        'expires_at'
    ];

    /**
     * The fields that will be transformed by Carbon.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'expires_at'
    ];
}

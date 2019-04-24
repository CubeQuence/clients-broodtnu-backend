<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'refresh_tokens';

    /**
     * The attributes that are required.
     *
     * @var array
     */
    public static $rules = [
        'user_id'  => 'required',
        'refresh_token' => 'required',
        'expires_at' => 'required',
    ];

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        //'active',
        //'address'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        //'active'
    ];

    /**
     * Get all the refresh_tokens by the user.
     *
     * TODO: add return
     */
    public function refreshTokens()
    {
        return $this->hasMany(RefreshToken::class, 'user_id');
    }
}
